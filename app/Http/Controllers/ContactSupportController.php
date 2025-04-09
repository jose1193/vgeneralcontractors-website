<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ContactSupport;
use App\Services\FacebookConversionApi;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Log;
use Throwable;

class ContactSupportController extends Controller
{
    protected TransactionService $transactionService;

    // Inject TransactionService via constructor
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Display the contact support form.
     */
    public function showForm()
    {
        // Retrieve the reCAPTCHA v3 Site Key using the config helper
        $recaptchaSiteKey = config('captcha.sitekey');

        // Pass the key to the view
        return view('contact-support', [
            'recaptchaSiteKey' => $recaptchaSiteKey
        ]);
    }

    /**
     * Store the submitted contact data via AJAX.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required|min:10',
            'sms_consent' => 'boolean',
            'g-recaptcha-response' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();
        
        // Verify reCAPTCHA token manually
        $recaptchaToken = $request->input('g-recaptcha-response');
        if (!$this->verifyRecaptchaToken($recaptchaToken)) {
            return response()->json([
                'success' => false,
                'message' => 'reCAPTCHA verification failed. Please try again.',
                'errors' => ['g-recaptcha-response' => ['CAPTCHA verification failed.']]
            ], 422);
        }

        try {
            $contact = $this->transactionService->run(
                // 1. Database operations
                function () use ($validatedData) {
                    $contact = ContactSupport::create([
                        'first_name' => $validatedData['first_name'],
                        'last_name' => $validatedData['last_name'],
                        'email' => $validatedData['email'],
                        'phone' => $validatedData['phone'],
                        'message' => $validatedData['message'],
                        'sms_consent' => $validatedData['sms_consent'] ?? false,
                    ]);

                    Log::info('Contact support record created', ['contact_id' => $contact->id]);
                    return $contact;
                },
                // 2. Post-Commit actions
                function ($createdContact) {
                    // Track Facebook event
                    try {
                        $fbApi = app(FacebookConversionApi::class);
                        $fbApi->lead([
                            'email' => $createdContact->email,
                            'phone' => $createdContact->phone,
                            'first_name' => $createdContact->first_name,
                            'last_name' => $createdContact->last_name,
                        ], [
                            'content_name' => 'Contact Support',
                            'content_type' => 'Customer Support',
                            'content_id' => 'support_request',
                            'event_id' => 'support_' . time() . '_' . substr(md5($createdContact->email), 0, 8),
                        ]);
                        Log::info('Facebook Conversion API lead event sent successfully');
                    } catch (Throwable $fbError) {
                        Log::error('Facebook API Error sending support lead: ' . $fbError->getMessage(), ['exception' => $fbError]);
                    }
                }
            );

            // If successful, return JSON response for AJAX
            return response()->json([
                'success' => true,
                'message' => 'Thank you for contacting us! We will get back to you shortly.'
            ]);

        } catch (Throwable $e) {
            Log::error('Error submitting contact support form: ' . $e->getMessage(), ['exception' => $e]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your request. Please try again later.'
            ], 500);
        }
    }

    /**
     * Validate a single field via AJAX.
     */
    public function validateField(Request $request)
    {
        $fieldName = $request->input('fieldName');
        $fieldValue = $request->input('fieldValue');

        if (!$fieldName) {
            return response()->json(['error' => 'Field name not provided.'], 400);
        }

        // Define validation rules
        $rules = [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required|min:10',
            'sms_consent' => 'boolean',
        ];

        if (!isset($rules[$fieldName])) {
            return response()->json(['error' => 'Invalid field name.'], 400);
        }

        // Create a validator instance for only the specified field
        $validator = Validator::make([$fieldName => $fieldValue], [$fieldName => $rules[$fieldName]]);

        if ($validator->fails()) {
            return response()->json(['valid' => false, 'errors' => $validator->errors()->get($fieldName)], 422);
        } else {
            return response()->json(['valid' => true]);
        }
    }

    /**
     * Verify reCAPTCHA token manually.
     * 
     * @param string $token The reCAPTCHA token
     * @return bool True if verification passed, false otherwise
     */
    private function verifyRecaptchaToken($token)
    {
        if (empty($token)) {
            return false;
        }

        try {
            $recaptchaSecret = config('captcha.secret');
            
            // Make a POST request to the Google reCAPTCHA API
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => $recaptchaSecret,
                    'response' => $token
                ]
            ]);
            
            $result = json_decode((string) $response->getBody(), true);
            
            // Log the verification for debugging
            Log::debug('reCAPTCHA verification result', [
                'result' => $result,
                'score' => $result['score'] ?? 'N/A'
            ]);
            
            // Check if successful and score is acceptable (0.5 is the default threshold)
            return isset($result['success']) && $result['success'] === true && 
                   (!isset($result['score']) || $result['score'] >= 0.5);
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification error', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}

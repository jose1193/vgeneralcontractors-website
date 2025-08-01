<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\FacebookLeadFormRequest; // We'll create this next
use App\Models\Appointment;
use App\Jobs\ProcessNewLead;
use App\Services\FacebookConversionApi;
use App\Services\TransactionService;
use Revolution\Google\Sheets\Facades\Sheets;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Throwable;
use Illuminate\Support\Facades\Config; // Import Config facade
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\AppointmentCollection;

class FacebookLeadFormController extends Controller
{
    protected TransactionService $transactionService;

    // Inject TransactionService via constructor
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Display the standalone Facebook lead form.
     */
    public function showForm()
    {
        // Retrieve the Google Maps API key (assuming it's set elsewhere, e.g., config/services.php)
        $googleMapsApiKey = Cache::remember('google_maps_api_key', 3600, function() {
            return config('services.google.maps_api_key');
        });
        
        // Retrieve the reCAPTCHA v3 Site Key using the config helper
        $recaptchaSiteKey = Cache::remember('recaptcha_site_key', 3600, function() {
            return config('captcha.sitekey');
        });

        // Pass both keys to the view
        return view('facebook-lead-form', [
            'googleMapsApiKey' => $googleMapsApiKey,
            'recaptchaSiteKey' => $recaptchaSiteKey // Pass the site key
        ]);
    }

    /**
     * Display the appointment confirmation page.
     */
    public function showConfirmation()
    {
        return view('appointment-confirmation')->with('appointment_success', 'Your appointment request has been received! We will contact you shortly to schedule your free inspection.');
    }

    /**
     * Store the submitted lead data via AJAX.
     * Uses FacebookLeadFormRequest for validation.
     */
    public function store(FacebookLeadFormRequest $request)
    {
        $validatedData = $request->validated(); // Get validated data
        
        // Verify reCAPTCHA token manually since we don't have the validation rule
        $recaptchaToken = $request->input('g-recaptcha-response');
        if (!$this->verifyRecaptchaToken($recaptchaToken)) {
            return response()->json([
                'success' => false,
                'message' => 'reCAPTCHA verification failed. Please try again.',
                'errors' => ['g-recaptcha-response' => ['CAPTCHA verification failed.']]
            ], 422);
        }
        
        // We don't need to save address_map_input to the database since it's just for UI
        // but we keep the extracted address fields (address, city, state, zipcode)

        try {
            $appointment = $this->transactionService->run(
                // 1. Database/Google Sheets operations
                function () use ($validatedData) {
                    $newAppointment = Appointment::create([
                        'uuid' => Str::uuid(),
                        'first_name' => $validatedData['first_name'],
                        'last_name' => $validatedData['last_name'],
                        'phone' => $validatedData['phone'],
                        'email' => $validatedData['email'],
                        'address' => $validatedData['address'],
                        'address_2' => $validatedData['address_2'] ?? null,
                        'city' => $validatedData['city'],
                        'state' => $validatedData['state'],
                        'zipcode' => $validatedData['zipcode'],
                        'country' => $validatedData['country'],
                        'insurance_property' => $validatedData['insurance_property'] === 'yes',
                        'message' => $validatedData['message'] ?? null,
                        'sms_consent' => filter_var($validatedData['sms_consent'] ?? false, FILTER_VALIDATE_BOOLEAN),
                        'registration_date' => Carbon::now(),
                        'inspection_status' => 'Pending',
                        'status_lead' => 'New',
                        'latitude' => $validatedData['latitude'] ?? null,
                        'longitude' => $validatedData['longitude'] ?? null,
                        'lead_source' => $validatedData['lead_source'] ?? 'Web'
                    ]);

                    // Clear caches related to appointments
                    $this->clearAppointmentCache();

                    // Prepare data for Google Sheets
                    $sheetName = Cache::remember('google_sheet_leads_active', 3600, function() {
                        return 'VG-Leads Active';
                    });
                    
                    $registrationDate = Carbon::now()->toDateTimeString();
                    $fullAddress = trim(($validatedData['address'] ?? '') . ' ' . ($validatedData['address_2'] ?? '')) . ', ' . ($validatedData['city'] ?? '') . ', ' . ($validatedData['state'] ?? '') . ' ' . ($validatedData['zipcode'] ?? '');

                    $values = [
                        ($validatedData['first_name'] ?? '') . ' ' . ($validatedData['last_name'] ?? ''),
                        "'" . ($validatedData['phone'] ?? ''),
                        $fullAddress,
                        $validatedData['email'] ?? '',
                        ($validatedData['insurance_property'] ?? 'no') === 'yes' ? 'Sí' : 'No',
                        $validatedData['state'] ?? '',
                        $registrationDate,
                        null, null, null, // Inspection date/time/confirmed
                        $validatedData['message'] ?? '',
                        null, null, null, null, null, null, // Other fields not from form
                        'Pending',
                        $newAppointment->uuid
                    ];

                    $sheetId = Cache::remember('google_sheet_id', 3600, function() {
                        return config('services.google.sheet_id');
                    });

                    Sheets::spreadsheet($sheetId)
                          ->sheet($sheetName)
                          ->append([$values]);

                    Log::info('Lead successfully added to Google Sheet via Facebook Form.', ['email' => $validatedData['email'], 'sheet_id' => $sheetId, 'sheet_name' => $sheetName]);

                    return $newAppointment;
                },
                // 2. Post-Commit actions
                function ($createdAppointment) {
                    Log::info('Transaction committed for Facebook Form Appointment. Running post-commit actions.', ['appointment_uuid' => $createdAppointment->uuid]);
                    ProcessNewLead::dispatch($createdAppointment);

                    // Track Facebook event (Make sure FacebookConversionApi is correctly configured)
                    try {
                         $fbApi = app(FacebookConversionApi::class);
                         $fbApi->lead([
                             'email' => $createdAppointment->email,
                             'phone' => $createdAppointment->phone,
                             'first_name' => $createdAppointment->first_name,
                             'last_name' => $createdAppointment->last_name,
                             'address' => $createdAppointment->address,
                             'address_2' => $createdAppointment->address_2,
                             'city' => $createdAppointment->city,
                             'state' => $createdAppointment->state,
                             'zip_code' => $createdAppointment->zipcode,
                             'country' => $createdAppointment->country,
                         ], [
                             'content_name' => 'Appointment Request (Facebook Form)',
                             'content_type' => 'Roof Inspection Service',
                             'content_id' => 'appointment_request_fb',
                             'event_id' => 'appointment_fb_' . time() . '_' . substr(md5($createdAppointment->email), 0, 8),
                         ]);
                         Log::info('Facebook Lead event tracked successfully.', ['appointment_uuid' => $createdAppointment->uuid]);
                    } catch (Throwable $fbError) {
                        Log::error('Failed to track Facebook Lead event.', [
                            'appointment_uuid' => $createdAppointment->uuid,
                            'error_message' => $fbError->getMessage()
                        ]);
                    }
                },
                // 3. Error actions (before rollback)
                function (Throwable $e) use ($validatedData) {
                    Log::error('Error occurred during Facebook Form transaction, before rollback.', [
                         'error_message' => $e->getMessage(),
                         'email' => $validatedData['email'] ?? 'N/A'
                    ]);
                }
            );

            // If successful, return JSON response for AJAX
            return response()->json([
                'success' => true,
                'message' => 'Your request has been submitted successfully!',
                'redirectUrl' => route('appointment.confirmation') // Send redirect URL to JS
            ]);

        } catch (Throwable $e) {
            // Log the main error that caused the transaction to fail or other issues
            Log::error('Failed to process Facebook lead form submission.', [
                'error_message' => $e->getMessage(),
                'email' => $validatedData['email'] ?? 'N/A',
                'trace' => $e->getTraceAsString() // Optional: for detailed debugging
            ]);

            // Return a generic error for AJAX
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500); // Use 500 for server error
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

        // Use the rules from the Form Request, but adapt for single field validation
        $formRequest = new FacebookLeadFormRequest();
        $rules = $formRequest->rules();

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
     * API endpoint to store lead data from external services.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeApi(Request $request)
    {
        // Validate the request data 
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'min:2', 'regex:/^[A-Za-z\'-]+$/'],
            'last_name' => ['required', 'min:2', 'regex:/^[A-Za-z\'-]+$/'],
            'phone' => 'required|regex:/^\(\d{3}\)\s\d{3}-\d{4}$/',
            'email' => 'required|email',
            'address' => 'required|min:5',
            'address_2' => 'nullable',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required|digits:5',
            'country' => 'required',
            'insurance_property' => 'required|in:yes,no',
            'message' => 'nullable|min:5',
            'sms_consent' => 'nullable|boolean',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'lead_source' => 'nullable|string',
            'status_lead' => 'nullable|string|in:New,Called,Pending,Declined',
            'inspection_date' => 'nullable|date|required_with:inspection_time',
            'inspection_time' => 'nullable|date_format:H:i|required_with:inspection_date',
            'api_key' => 'required' // Add API key validation for security
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false, 
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();
        
        // Check API key
        $apiKey = Cache::remember('facebook_lead_api_key', 3600, function() {
            return config('services.facebook_lead.api_key');
        });
        
        if ($validatedData['api_key'] !== $apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API key'
            ], 401);
        }
        
        // Remove api_key from data to be saved
        unset($validatedData['api_key']);

        try {
            $appointment = $this->transactionService->run(
                // Database/Google Sheets operations
                function () use ($validatedData) {
                    $newAppointment = Appointment::create([
                        'uuid' => Str::uuid(),
                        'first_name' => $validatedData['first_name'],
                        'last_name' => $validatedData['last_name'],
                        'phone' => $validatedData['phone'],
                        'email' => $validatedData['email'],
                        'address' => $validatedData['address'],
                        'address_2' => $validatedData['address_2'] ?? null,
                        'city' => $validatedData['city'],
                        'state' => $validatedData['state'],
                        'zipcode' => $validatedData['zipcode'],
                        'country' => $validatedData['country'],
                        'insurance_property' => $validatedData['insurance_property'] === 'yes',
                        'message' => $validatedData['message'] ?? null,
                        'sms_consent' => filter_var($validatedData['sms_consent'] ?? false, FILTER_VALIDATE_BOOLEAN),
                        'registration_date' => Carbon::now(),
                        'inspection_status' => 'Pending',
                        'status_lead' => 'New',
                        'latitude' => $validatedData['latitude'] ?? null,
                        'longitude' => $validatedData['longitude'] ?? null,
                        'lead_source' => $validatedData['lead_source'] ?? 'Facebook'
                    ]);

                    // Clear caches related to appointments
                    $this->clearAppointmentCache();

                    // Similar sheet update logic as in store method
                    $sheetName = Cache::remember('google_sheet_leads_active', 3600, function() {
                        return 'VG-Leads Active';
                    });
                    
                    $registrationDate = Carbon::now()->toDateTimeString();
                    $fullAddress = trim(($validatedData['address'] ?? '') . ' ' . ($validatedData['address_2'] ?? '')) . ', ' . ($validatedData['city'] ?? '') . ', ' . ($validatedData['state'] ?? '') . ' ' . ($validatedData['zipcode'] ?? '');

                    $values = [
                        ($validatedData['first_name'] ?? '') . ' ' . ($validatedData['last_name'] ?? ''),
                        "'" . ($validatedData['phone'] ?? ''),
                        $fullAddress,
                        $validatedData['email'] ?? '',
                        ($validatedData['insurance_property'] ?? 'no') === 'yes' ? 'Sí' : 'No',
                        $validatedData['state'] ?? '',
                        $registrationDate,
                        null, null, null, // Inspection date/time/confirmed
                        $validatedData['message'] ?? '',
                        null, null, null, null, null, null, // Other fields not from form
                        'Pending',
                        $newAppointment->uuid
                    ];

                    $sheetId = Cache::remember('google_sheet_id', 3600, function() {
                        return config('services.google.sheet_id');
                    });

                    Sheets::spreadsheet($sheetId)
                          ->sheet($sheetName)
                          ->append([$values]);

                    Log::info('API Lead successfully added to Google Sheet.', ['email' => $validatedData['email']]);

                    return $newAppointment;
                },
                // Post-Commit actions
                function ($createdAppointment) {
                    Log::info('API Transaction committed for Appointment. Running post-commit actions.', ['appointment_uuid' => $createdAppointment->uuid]);
                    ProcessNewLead::dispatch($createdAppointment);
                },
                // Error actions (before rollback)
                function (Throwable $e) use ($validatedData) {
                    Log::error('Error occurred during API transaction, before rollback.', [
                         'error_message' => $e->getMessage(),
                         'email' => $validatedData['email'] ?? 'N/A'
                    ]);
                }
            );

            // Return success response with resource
            return response()->json([
                'success' => true,
                'message' => 'Lead successfully created',
                'data' => new AppointmentResource($appointment)
            ], 201);

        } catch (Throwable $e) {
            Log::error('Failed to process API lead submission.', [
                'error_message' => $e->getMessage(),
                'email' => $validatedData['email'] ?? 'N/A'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request'
            ], 500);
        }
    }

    /**
     * API endpoint to get all leads.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllLeads(Request $request)
    {
        // Check API key
        $apiKey = Cache::remember('facebook_lead_api_key', 3600, function() {
            return config('services.facebook_lead.api_key');
        });
        
        if ($request->header('X-API-KEY') !== $apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 401);
        }
        
        try {
            // Optional pagination parameters
            $perPage = $request->input('per_page', 15);
            $page = $request->input('page', 1);
            
            // Optional filters
            $filters = [];
            if ($request->has('status')) {
                $filters['inspection_status'] = $request->input('status');
            }
            
            // Generate cache key based on parameters
            $cacheKey = "leads_" . $page . "_" . $perPage . "_" . md5(json_encode($filters));
            
            // Get results with cache
            $leads = Cache::remember($cacheKey, 300, function() use ($filters, $perPage, $page) {
                // Build query
                $query = Appointment::query();
                
                // Apply filters
                foreach ($filters as $column => $value) {
                    $query->where($column, $value);
                }
                
                // Get paginated results
                return $query->orderBy('registration_date', 'desc')
                           ->paginate($perPage, ['*'], 'page', $page);
            });
            
            return response()->json([
                'success' => true,
                'data' => new AppointmentCollection($leads),
            ]);
            
        } catch (Throwable $e) {
            Log::error('Failed to fetch leads via API.', [
                'error_message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving leads'
            ], 500);
        }
    }

    /**
     * Clear caches related to appointments
     */
    private function clearAppointmentCache()
    {
        // Clear all appointment-related caches
        $cacheKeys = [
            'appointments_count', 
            'appointments_pending',
            'appointments_recent'
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Also clear paginated lead caches - pattern-based deletion
        $keys = Cache::get('cache_keys_leads', []);
        foreach ($keys as $key) {
            if (Str::startsWith($key, 'leads_')) {
                Cache::forget($key);
            }
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
            $recaptchaSecret = Cache::remember('recaptcha_secret', 3600, function() {
                return config('captcha.secret');
            });
            
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

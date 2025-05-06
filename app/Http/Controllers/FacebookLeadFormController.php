<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\FacebookLeadFormRequest;
use App\Models\Appointment;
use App\Jobs\ProcessNewLead;
use App\Services\FacebookConversionApi;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Throwable;
use Illuminate\Support\Facades\Config;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\AppointmentCollection;
use App\Traits\CacheTrait;

class FacebookLeadFormController extends Controller
{
    use CacheTrait;
    
    protected TransactionService $transactionService;
    public $search = '';
    public $sortField = 'registration_date';
    public $sortDirection = 'desc';
    public $perPage = 15;
    public $showDeleted = false;
    protected $significantDataChange = false;

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
        $googleMapsApiKey = Cache::remember('google_maps_api_key', 3600, function() {
            return config('services.google.maps_api_key');
        });
        
        $recaptchaSiteKey = Cache::remember('recaptcha_site_key', 3600, function() {
            return config('captcha.sitekey');
        });

        return view('facebook-lead-form', [
            'googleMapsApiKey' => $googleMapsApiKey,
            'recaptchaSiteKey' => $recaptchaSiteKey
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
     */
    public function store(FacebookLeadFormRequest $request)
    {
        $validatedData = $request->validated();
        
        // Verify reCAPTCHA token
        $recaptchaToken = $request->input('g-recaptcha-response');
        if (!$this->verifyRecaptchaToken($recaptchaToken)) {
            return response()->json([
                'success' => false,
                'message' => 'reCAPTCHA verification failed. Please try again.',
                'errors' => ['g-recaptcha-response' => ['CAPTCHA verification failed.']]
            ], 422);
        }

        try {
            $appointment = $this->transactionService->run(
                // Database operations
                function () use ($validatedData) {
                    Log::info('Creating appointment with data:', [
                        'first_name' => $validatedData['first_name'],
                        'status_lead' => 'New'
                    ]);
                
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
                        'lead_source' => $validatedData['lead_source'] ?? 'Facebook Ads'
                    ]);

                    Log::info('Appointment created:', [
                        'id' => $newAppointment->id,
                        'status_lead' => $newAppointment->status_lead,
                        'fresh_status' => $newAppointment->fresh()->status_lead
                    ]);

                    $this->significantDataChange = true;
                    $this->clearCache('appointments');

                    Log::info('Lead successfully created via Facebook Form.', ['email' => $validatedData['email']]);

                    return $newAppointment;
                },
                // Post-Commit actions
                function ($createdAppointment) {
                    Log::info('Transaction committed for Facebook Form Appointment.', ['appointment_uuid' => $createdAppointment->uuid]);
                    ProcessNewLead::dispatch($createdAppointment);

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
                // Error actions (before rollback)
                function (Throwable $e) use ($validatedData) {
                    Log::error('Error occurred during Facebook Form transaction.', [
                        'error_message' => $e->getMessage(),
                        'email' => $validatedData['email'] ?? 'N/A'
                    ]);
                }
            );

            return response()->json([
                'success' => true,
                'message' => 'Your request has been submitted successfully!',
                'redirectUrl' => route('facebook.confirmation')
            ]);

        } catch (Throwable $e) {
            Log::error('Failed to process Facebook lead form submission.', [
                'error_message' => $e->getMessage(),
                'email' => $validatedData['email'] ?? 'N/A',
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.'
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

        $formRequest = new FacebookLeadFormRequest();
        $rules = $formRequest->rules();

        if (!isset($rules[$fieldName])) {
            return response()->json(['error' => 'Invalid field name.'], 400);
        }

        $validator = Validator::make([$fieldName => $fieldValue], [$fieldName => $rules[$fieldName]]);

        if ($validator->fails()) {
            return response()->json(['valid' => false, 'errors' => $validator->errors()->get($fieldName)], 422);
        } else {
            return response()->json(['valid' => true]);
        }
    }

    /**
     * API endpoint to store lead data from external services.
     */
    public function storeApi(Request $request)
    {
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
            'insurance_property' => 'required|string',
            'intent_to_claim' => 'nullable|string',
            'message' => 'nullable|min:5',
            'notes' => 'nullable|string',
            'damage_detail' => 'nullable|string',
            'sms_consent' => 'nullable|boolean',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'lead_source' => 'nullable|string',
            'status_lead' => 'nullable|string|in:New,Called,Pending,Declined',
            'inspection_date' => 'nullable|date|required_with:inspection_time',
            'inspection_time' => 'nullable|date_format:H:i|required_with:inspection_date',
            'api_key' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false, 
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();
        
        // Usar directamente la variable de entorno en lugar de cache y config
        $apiKey = env('API_KEY_STORE_API_REST');
        
        if ($validatedData['api_key'] !== $apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API key'
            ], 401);
        }
        
        unset($validatedData['api_key']);

        try {
            $appointment = $this->transactionService->run(
                // Database operations
                function () use ($validatedData) {
                    Log::info('Creating API appointment with data:', [
                        'first_name' => $validatedData['first_name'],
                        'status_lead_input' => $validatedData['status_lead'] ?? 'Not provided', 
                        'status_lead_default' => 'New'
                    ]);
                
                    // Convertir insurance_property a boolean correctamente aceptando diferentes variaciones
                    $insuranceProperty = false;
                    if (is_string($validatedData['insurance_property'])) {
                        $value = strtolower($validatedData['insurance_property']);
                        $insuranceProperty = in_array($value, ['yes', 'y', 'true', '1', 'si', 'sí']);
                    } elseif (is_bool($validatedData['insurance_property'])) {
                        $insuranceProperty = $validatedData['insurance_property'];
                    } elseif (is_numeric($validatedData['insurance_property'])) {
                        $insuranceProperty = (bool)$validatedData['insurance_property'];
                    }
                    
                    // Convertir intent_to_claim a boolean con la misma lógica
                    $intentToClaim = false;
                    if (isset($validatedData['intent_to_claim'])) {
                        if (is_string($validatedData['intent_to_claim'])) {
                            $value = strtolower($validatedData['intent_to_claim']);
                            $intentToClaim = in_array($value, ['yes', 'y', 'true', '1', 'si', 'sí']);
                        } elseif (is_bool($validatedData['intent_to_claim'])) {
                            $intentToClaim = $validatedData['intent_to_claim'];
                        } elseif (is_numeric($validatedData['intent_to_claim'])) {
                            $intentToClaim = (bool)$validatedData['intent_to_claim'];
                        }
                    }

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
                        'insurance_property' => $insuranceProperty,
                        'intent_to_claim' => $intentToClaim,
                        'message' => $validatedData['message'] ?? null,
                        'notes' => $validatedData['notes'] ?? null,
                        'damage_detail' => $validatedData['damage_detail'] ?? null,
                        'sms_consent' => filter_var($validatedData['sms_consent'] ?? false, FILTER_VALIDATE_BOOLEAN),
                        'registration_date' => Carbon::now(),
                        'inspection_status' => 'Pending',
                        'status_lead' => 'New',
                        'latitude' => $validatedData['latitude'] ?? null,
                        'longitude' => $validatedData['longitude'] ?? null,
                        'lead_source' => $validatedData['lead_source'] ?? 'Website'
                    ]);

                    Log::info('API Appointment created:', [
                        'id' => $newAppointment->id,
                        'status_lead' => $newAppointment->status_lead,
                        'fresh_status' => $newAppointment->fresh()->status_lead
                    ]);

                    $this->significantDataChange = true;
                    $this->clearCache('appointments');

                    Log::info('API Lead successfully created.', ['email' => $validatedData['email']]);

                    return $newAppointment;
                },
                // Post-Commit actions
                function ($createdAppointment) {
                    Log::info('API Transaction committed for Appointment.', ['appointment_uuid' => $createdAppointment->uuid]);
                    ProcessNewLead::dispatch($createdAppointment);
                },
                // Error actions (before rollback)
                function (Throwable $e) use ($validatedData) {
                    Log::error('Error occurred during API transaction.', [
                        'error_message' => $e->getMessage(),
                        'email' => $validatedData['email'] ?? 'N/A'
                    ]);
                }
            );

            return response()->json([
                'success' => true,
                'message' => 'Lead successfully created',
                'data' => new AppointmentResource($appointment)
            ], 200);

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
     */
    public function getAllLeads(Request $request)
    {
        // También actualizar esta validación para usar la misma variable de entorno
        $apiKey = env('API_KEY_STORE_API_REST');
        
        if ($request->header('X-API-KEY') !== $apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 401);
        }
        
        try {
            $this->perPage = $request->input('per_page', 15);
            $page = $request->input('page', 1);
            $this->search = $request->input('search', '');
            
            $filters = [];
            if ($request->has('status')) {
                $filters['inspection_status'] = $request->input('status');
            }
            
            $cacheKey = $this->generateCacheKey('appointments', $page);
            
            $leads = Cache::remember($cacheKey, 300, function() use ($filters, $page) {
                $query = Appointment::query();
                
                if (!empty($this->search)) {
                    $searchTerm = '%' . $this->search . '%';
                    $query->where(function($q) use ($searchTerm) {
                        $q->where('first_name', 'like', $searchTerm)
                          ->orWhere('last_name', 'like', $searchTerm)
                          ->orWhere('email', 'like', $searchTerm)
                          ->orWhere('phone', 'like', $searchTerm)
                          ->orWhere('address', 'like', $searchTerm);
                    });
                }
                
                foreach ($filters as $column => $value) {
                    $query->where($column, $value);
                }
                
                return $query->orderBy($this->sortField, $this->sortDirection)
                           ->paginate($this->perPage, ['*'], 'page', $page);
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
     * Clear caches related to appointments - Legacy method
     */
    private function clearAppointmentCache()
    {
        $this->clearCache('appointments');
        
        $specificCacheKeys = [
            'appointments_count', 
            'appointments_pending',
            'appointments_recent'
        ];

        foreach ($specificCacheKeys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Verify reCAPTCHA token manually.
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
            
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => $recaptchaSecret,
                    'response' => $token
                ]
            ]);
            
            $result = json_decode((string) $response->getBody(), true);
            
            Log::debug('reCAPTCHA verification result', [
                'result' => $result,
                'score' => $result['score'] ?? 'N/A'
            ]);
            
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

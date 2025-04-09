<?php

namespace App\Livewire;

use Livewire\Component;
// Correct Model Import:
use App\Models\ContactSupport as ContactSupportModel; // Alias to avoid naming conflict with class
use App\Services\FacebookConversionApi;
use Livewire\Attributes\Rule;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Log;
use Throwable; // Keep this if TransactionService might throw it

class ContactSupport extends Component // Class name matches the model name, which is okay but can be confusing.
{
    #[Rule('required|min:2')]
    public $first_name = '';

    #[Rule('required|min:2')]
    public $last_name = '';

    #[Rule('required|email')]
    public $email = '';

    #[Rule('required')]
    public $phone = '';

    #[Rule('required|min:10')]
    public $message = '';

    #[Rule('boolean')]
    public $sms_consent = false;

    #[Rule('required|nocaptcha')]
    public $captcha = null;

    // Constructor Injection remains the same
    public function __construct(
        public TransactionService $transactionService
    )
    {
        // Constructor injection
    }

    public function submit()
    {
        Log::info('Contact support form submission started', [
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name
        ]);
        
        // Verify reCAPTCHA token before validation
        if (!$this->verifyRecaptchaToken($this->captcha)) {
            Log::error('reCAPTCHA verification failed', ['email' => $this->email]);
            $this->dispatch('support-request-error', message: 'CAPTCHA verification failed. Please try again.');
            return;
        }
        
        Log::info('reCAPTCHA verification passed');
        
        $this->validate();
        
        Log::info('Contact support form validation passed');
        
        try {
            Log::info('Starting transaction for contact support submission');
            $this->transactionService->run(function () { 
                // Use the correct Model
                $contact = ContactSupportModel::create([
                    'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'email' => $this->email,
                    'phone' => $this->phone, // Ensure phone formatting is okay for DB
                    'message' => $this->message,
                    'sms_consent' => (bool) $this->sms_consent,
                    // 'type' => 'support', // Note: 'type' is NOT in your ContactSupport model's $fillable. Remove or add to $fillable.
                ]);
                
                Log::info('Contact support record created', ['contact_id' => $contact->id]);

                // Rastrear el evento Lead con Facebook Conversions API
                $fbApi = app(FacebookConversionApi::class);

                try {
                    $fbApi->lead([
                        'email' => $this->email,
                        'phone' => $this->phone, // Consider hashing phone if required by FB/privacy
                        'first_name' => $this->first_name,
                        'last_name' => $this->last_name,
                    ], [
                        'content_name' => 'Contact Support',
                        'content_type' => 'Customer Support', // Changed from Lead to Customer Support
                        'content_id' => 'support_request',
                        'event_id' => 'support_' . time() . '_' . substr(md5($this->email), 0, 8),
                    ]);
                    
                    Log::info('Facebook Conversion API lead event sent successfully');
                } catch (\Exception $e) {
                    // Registrar el error pero no afectar la experiencia del usuario
                    Log::error('Facebook API Error sending support lead: ' . $e->getMessage(), ['exception' => $e]);
                    // Optionally notify monitoring service
                }

                // Dispatch success event
                $this->dispatch('support-request-success', message: 'Thank you for contacting us! We will get back to you shortly.');

            }); // End transaction closure
            
            Log::info('Transaction completed successfully for contact support submission');

             // Reset form fields only if transaction was successful
            $this->resetFormFields();
            Log::info('Form fields reset after successful submission');

        } catch (\Throwable $e) { // Catch potential errors from transactionService->run() itself or within
            Log::error('Error submitting contact support form: ' . $e->getMessage(), ['exception' => $e]);
            // Dispatch an error event to the frontend if desired
            $this->dispatch('support-request-error', message: 'An error occurred while submitting your request. Please try again later.');
            // Optionally re-throw or handle specific exceptions differently
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
            Log::warning('Empty reCAPTCHA token provided');
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

    // Helper method to reset form fields
    protected function resetFormFields()
    {
        $this->reset(['first_name', 'last_name', 'email', 'phone', 'message', 'sms_consent', 'captcha']);
        // Note: Resetting captcha might require specific handling depending on the implementation
        // You might need to trigger a JS function to reset the captcha widget.
        // Example: $this->dispatch('reset-captcha');
    }


    public function render()
    {
        return view('livewire.contact-support');
    }
}
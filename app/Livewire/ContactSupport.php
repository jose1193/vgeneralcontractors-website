<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmailData;
use App\Services\FacebookConversionApi;
use Livewire\Attributes\Rule;

class ContactSupport extends Component
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

    public function submit()
    {
        $this->validate();

        EmailData::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
            'sms_consent' => (bool) $this->sms_consent,
            'type' => 'support',
        ]);

        // Rastrear el evento Lead con Facebook Conversions API
        $fbApi = app(FacebookConversionApi::class);
        
        try {
            $fbApi->lead([
                'email' => $this->email,
                'phone' => $this->phone,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
            ], [
                'content_name' => 'Contact Support',
                'content_type' => 'Customer Support',
                'content_id' => 'support_request',
                'event_id' => 'support_' . time() . '_' . substr(md5($this->email), 0, 8),
            ]);
        } catch (\Exception $e) {
            // Registrar el error pero no afectar la experiencia del usuario
            \Log::error('Facebook API Error: ' . $e->getMessage());
        }

        // session()->flash('success', 'Thank you for contacting us! We will get back to you shortly.');
        $this->dispatch('support-request-success', message: 'Thank you for contacting us! We will get back to you shortly.');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.contact-support');
    }
} 
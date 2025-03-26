<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmailData;
use App\Services\FacebookConversionApi;

class ContactSupport extends Component
{
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';
    public $message = '';

    protected $rules = [
        'first_name' => 'required|min:2',
        'last_name' => 'required|min:2',
        'email' => 'required|email',
        'phone' => 'required',
        'message' => 'required|min:10',
    ];

    protected $messages = [
        'first_name.required' => 'Please enter your first name.',
        'last_name.required' => 'Please enter your last name.',
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please enter a valid email address.',
        'phone.required' => 'Please enter your phone number.',
        'message.required' => 'Please enter your message.',
        'message.min' => 'Your message should be at least 10 characters.',
    ];

    public function submit()
    {
        $this->validate();

        EmailData::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
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

        session()->flash('success', 'Thank you for contacting us! We will get back to you shortly.');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.contact-support');
    }
} 
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmailData;

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

        session()->flash('success', 'Thank you for contacting us! We will get back to you shortly.');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.contact-support');
    }
} 
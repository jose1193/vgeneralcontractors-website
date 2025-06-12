<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use App\Jobs\ProcessNewLead;
use App\Services\FacebookConversionApi;
use Illuminate\Support\Str;

class AppointmentForm extends Component
{
    public $name;
    public $phone;
    public $email;
    public $city;
    public $zipcode;
    public $insurance;
    public $message;
    public $sms_consent = false;
    public $success = false;
    public $show = false;

    protected $rules = [
        'name' => 'required|min:2',
        'phone' => 'required|regex:/^\(\d{3}\)\s\d{3}-\d{4}$/',
        'email' => 'required|email',
        'city' => 'required|in:Houston,Dallas',
        'zipcode' => 'required|digits:5',
        'insurance' => 'required|in:yes,no',
        'message' => 'nullable|min:10',
        'sms_consent' => 'boolean'
    ];

    protected $listeners = [
        'openAppointmentModal' => 'open',
        'closeAppointmentModal' => 'closeModal'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function open()
    {
        $this->show = true;
        $this->dispatchBrowserEvent('open-appointment-modal');
    }

    public function closeModal()
    {
        $this->show = false;
        $this->dispatchBrowserEvent('close-appointment-modal');
        $this->reset(['name', 'phone', 'email', 'city', 'zipcode', 'insurance', 'message', 'sms_consent', 'success']);
    }

    public function submit()
    {
        $this->validate();

        $appointment = Appointment::create([
            'uuid' => Str::uuid(),
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'city' => $this->city,
            'zipcode' => $this->zipcode,
            'insurance' => $this->insurance === 'yes' ? 'Si' : 'No',
            'message' => $this->message,
            'sms_consent' => $this->sms_consent
        ]);

        // Dispatch the job to process the new lead
        ProcessNewLead::dispatch($appointment);
        
        // Rastrear el evento Lead con Facebook Conversions API
        $fbApi = app(FacebookConversionApi::class);
        
        $names = explode(' ', $this->name, 2);
        $firstName = $names[0];
        $lastName = isset($names[1]) ? $names[1] : '';
        
        $fbApi->lead([
            'email' => $this->email,
            'phone' => $this->phone,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'city' => $this->city,
            'zip_code' => $this->zipcode,
        ], [
            'content_name' => 'Appointment Request',
            'content_type' => 'Roof Inspection Service',
            'content_id' => 'appointment_request',
            'event_id' => 'appointment_' . time() . '_' . substr(md5($this->email), 0, 8),
        ]);

        $this->reset(['name', 'phone', 'email', 'city', 'zipcode', 'insurance', 'message', 'sms_consent']);
        $this->success = true;
    }

    public function render()
    {
        return view('livewire.appointment-form');
    }
}

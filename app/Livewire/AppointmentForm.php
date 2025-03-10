<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use App\Jobs\ProcessNewLead;
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

        $this->reset(['name', 'phone', 'email', 'city', 'zipcode', 'insurance', 'message', 'sms_consent']);
        $this->success = true;
    }

    public function render()
    {
        return view('livewire.appointment-form');
    }
}

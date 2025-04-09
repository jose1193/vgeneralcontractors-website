<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;

class NewLeadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Combine first and last name for the view if needed, or pass separately
        $full_name = $this->appointment->first_name . ' ' . $this->appointment->last_name;

        return (new MailMessage)
            ->subject('🚨 New Lead Alert! 🔔')
            ->view('emails.new-lead', [
                // Pass the whole appointment object for flexibility
                'appointment' => $this->appointment,
                // Or pass specific data if preferred
                /*
                'data' => [
                    'first_name' => $this->appointment->first_name,
                    'last_name' => $this->appointment->last_name,
                    'full_name' => $full_name, // Combined name
                    'email' => $this->appointment->email,
                    'phone_number' => $this->appointment->phone,
                    'address' => $this->appointment->address,
                    'address_2' => $this->appointment->address_2,
                    'city' => $this->appointment->city,
                    'state' => $this->appointment->state,
                    'zip_code' => $this->appointment->zipcode,
                    'country' => $this->appointment->country,
                    'insurance_property' => $this->appointment->insurance_property, // Use new field
                    'message' => $this->appointment->message,
                    'sms_consent' => $this->appointment->sms_consent ? 'Yes' : 'No',
                    'registration_date' => $this->appointment->registration_date ? $this->appointment->registration_date->format('Y-m-d H:i:s') : 'N/A',
                ]
                */
            ]);
    }
} 
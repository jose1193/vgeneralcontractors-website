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
        return (new MailMessage)
            ->subject('🚨 New Lead Alert! 🔔')
            ->view('emails.new-lead', [
                'data' => [
                    'full_name' => $this->appointment->name,
                    'email' => $this->appointment->email,
                    'phone_number' => $this->appointment->phone,
                    'city' => $this->appointment->city,
                    'zip_code' => $this->appointment->zipcode,
                    'tiene_seguro' => $this->appointment->insurance
                ]
            ]);
    }
} 
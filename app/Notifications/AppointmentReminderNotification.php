<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;
    protected $isInternal;

    public function __construct(Appointment $appointment, bool $isInternal = false)
    {
        $this->appointment = $appointment;
        $this->isInternal = $isInternal;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        if ($this->isInternal) {
            return (new MailMessage)
                ->subject('Appointment Reminder - Internal Notification')
                ->markdown('emails.appointment-reminder', [
                    'appointment' => $this->appointment,
                    'isInternal' => true
                ]);
        }

        return (new MailMessage)
            ->subject('Reminder: Your Upcoming Appointment')
            ->markdown('emails.appointment-reminder', [
                'appointment' => $this->appointment,
                'isInternal' => false
            ]);
    }
} 
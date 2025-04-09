<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;

class LeadConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;

    /**
     * Create a new notification instance.
     *
     * @param Appointment $appointment
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // We only need the mail channel for this notification
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Combine first and last name for greeting
        $fullName = $this->appointment->first_name . ' ' . $this->appointment->last_name;

        return (new MailMessage)
            ->subject('✅Hemos recibido sus datos - Próximo paso: Su inspección gratuita')
            ->view('emails.lead-confirmation', [
                'appointment' => $this->appointment, // Pass the full appointment if needed
                'full_name' => $fullName         // Pass the combined name for the template
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            // If you needed to send this notification via other channels (e.g., database)
        ];
    }
} 
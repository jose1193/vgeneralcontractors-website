<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Models\CompanyData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminRejectionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;
    protected $noContact;
    protected $noInsurance;
    protected $otherReason;
    protected $companyData;

    /**
     * Create a new notification instance.
     *
     * @param  Appointment  $appointment
     * @param  bool  $noContact
     * @param  bool  $noInsurance
     * @param  string|null  $otherReason
     * @param  CompanyData|null  $companyData
     * @return void
     */
    public function __construct(Appointment $appointment, bool $noContact = false, bool $noInsurance = false, ?string $otherReason = null, ?CompanyData $companyData = null)
    {
        $this->appointment = $appointment;
        $this->noContact = $noContact;
        $this->noInsurance = $noInsurance;
        $this->otherReason = $otherReason;
        $this->companyData = $companyData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $reasons = [];
        if ($this->noContact) {
            $reasons[] = 'No fue posible contactar';
        }
        if ($this->noInsurance) {
            $reasons[] = 'No tiene seguro de propiedad';
        }
        if ($this->otherReason) {
            $reasons[] = 'Otro: ' . $this->otherReason;
        }
        
        $reasonsText = implode(', ', $reasons);
        
        return (new MailMessage)
            ->subject('Notificación Admin: Solicitud Rechazada - ' . $this->appointment->first_name . ' ' . $this->appointment->last_name)
            ->line('Se ha rechazado una solicitud de inspección:')
            ->line('Cliente: ' . $this->appointment->first_name . ' ' . $this->appointment->last_name)
            ->line('Email: ' . $this->appointment->email)
            ->line('Teléfono: ' . $this->appointment->phone)
            ->line('Dirección: ' . $this->appointment->address)
            ->line('Ciudad: ' . $this->appointment->city . ', ' . $this->appointment->state)
            ->line('Razones del rechazo: ' . $reasonsText)
            ->line('Fecha de rechazo: ' . now()->format('d/m/Y H:i:s'))
            ->line('Esta es una notificación automática del sistema.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'appointment_uuid' => $this->appointment->uuid,
            'customer_name' => $this->appointment->first_name . ' ' . $this->appointment->last_name,
            'customer_email' => $this->appointment->email,
            'no_contact' => $this->noContact,
            'no_insurance' => $this->noInsurance,
            'other_reason' => $this->otherReason,
        ];
    }
}

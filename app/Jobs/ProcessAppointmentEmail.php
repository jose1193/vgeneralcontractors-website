<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;
use App\Models\EmailData;
use App\Notifications\AppointmentConfirmedNotification;
use App\Notifications\AppointmentCancelledNotification;
use App\Notifications\AppointmentRescheduledNotification;
use App\Notifications\AppointmentReminderNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class ProcessAppointmentEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $appointment;
    protected $emailType;

    public function __construct(Appointment $appointment, string $emailType)
    {
        $this->appointment = $appointment;
        $this->emailType = $emailType;
    }

    public function handle()
    {
        try {
            // Send notification to the client
            if ($this->appointment->email) {
                switch ($this->emailType) {
                    case 'confirmed':
                        Notification::route('mail', $this->appointment->email)
                            ->notify(new AppointmentConfirmedNotification($this->appointment));
                        break;
                    case 'cancelled':
                        Notification::route('mail', $this->appointment->email)
                            ->notify(new AppointmentCancelledNotification($this->appointment));
                        break;
                    case 'rescheduled':
                        Notification::route('mail', $this->appointment->email)
                            ->notify(new AppointmentRescheduledNotification($this->appointment));
                        break;
                    case 'reminder':
                        Notification::route('mail', $this->appointment->email)
                            ->notify(new AppointmentReminderNotification($this->appointment));
                        break;
                }
                Log::info("Appointment {$this->emailType} email sent to: " . $this->appointment->email . ' for appointment UUID: ' . $this->appointment->uuid);
            } else {
                Log::warning('Appointment does not have an email address. Cannot send notification. UUID: ' . $this->appointment->uuid);
            }

            // Send internal notification
            $appointmentEmailData = EmailData::where('type', 'Admin')->first();
            if ($appointmentEmailData && $appointmentEmailData->email) {
                switch ($this->emailType) {
                    case 'confirmed':
                        Notification::route('mail', $appointmentEmailData->email)
                            ->notify(new AppointmentConfirmedNotification($this->appointment, true));
                        break;
                    case 'cancelled':
                        Notification::route('mail', $appointmentEmailData->email)
                            ->notify(new AppointmentCancelledNotification($this->appointment, true));
                        break;
                    case 'rescheduled':
                        Notification::route('mail', $appointmentEmailData->email)
                            ->notify(new AppointmentRescheduledNotification($this->appointment, true));
                        break;
                    case 'reminder':
                        Notification::route('mail', $appointmentEmailData->email)
                            ->notify(new AppointmentReminderNotification($this->appointment, true));
                        break;
                }
                Log::info("Internal appointment {$this->emailType} notification sent for appointment UUID: " . $this->appointment->uuid);
            } else {
                Log::warning('Could not find appointment email address in EmailData table.');
            }
        } catch (\Exception $e) {
            Log::error("Error sending appointment {$this->emailType} email for appointment UUID: " . $this->appointment->uuid . ' - Error: ' . $e->getMessage());
        }
    }
} 
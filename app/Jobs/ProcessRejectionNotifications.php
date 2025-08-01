<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;
use App\Models\CompanyData;
use App\Notifications\AppointmentRejectionNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class ProcessRejectionNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $appointmentIds;
    protected $noContact;
    protected $noInsurance;
    protected $otherReason;

    /**
     * Create a new job instance.
     *
     * @param array $appointmentIds
     * @param bool $noContact
     * @param bool $noInsurance
     * @param string|null $otherReason
     * @return void
     */
    public function __construct(array $appointmentIds, bool $noContact, bool $noInsurance, ?string $otherReason)
    {
        $this->appointmentIds = $appointmentIds;
        $this->noContact = $noContact;
        $this->noInsurance = $noInsurance;
        $this->otherReason = $otherReason;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Obtener información de la compañía
        $companyData = CompanyData::first();
        
        if (!$companyData) {
            Log::warning("No se encontró información de la compañía para las notificaciones de rechazo");
        }
        
        foreach ($this->appointmentIds as $appointmentId) {
            try {
                $appointment = Appointment::where('uuid', $appointmentId)->first();
                
                if (!$appointment) {
                    Log::warning("Cita con UUID {$appointmentId} no encontrada al procesar rechazo.");
                    continue;
                }
                
                // Omitir si no hay correo electrónico
                if (empty($appointment->email)) {
                    Log::warning("Cita con UUID {$appointmentId} no tiene dirección de correo. Omitiendo notificación de rechazo.");
                    continue;
                }
                
                // 1. Enviar notificación al cliente
                Notification::route('mail', $appointment->email)
                    ->notify(new AppointmentRejectionNotification(
                        $appointment, 
                        $this->noContact, 
                        $this->noInsurance, 
                        $this->otherReason,
                        $companyData
                    ));
                
                // 2. Enviar copia al administrador
                $adminEmailData = \App\Models\EmailData::where('type', 'Admin')->first();
                if ($adminEmailData && $adminEmailData->email) {
                    Notification::route('mail', $adminEmailData->email)
                        ->notify(new AppointmentRejectionNotification(
                            $appointment, 
                            $this->noContact, 
                            $this->noInsurance, 
                            $this->otherReason,
                            $companyData
                        ));
                    Log::info("Notificación de rechazo enviada al administrador para la cita UUID: {$appointmentId}");
                } else {
                    Log::warning("No se pudo encontrar la dirección de correo del administrador en la tabla EmailData. Omitiendo notificación de rechazo al administrador.");
                }
                
                // Actualizar estado de la cita a rechazada
                $appointment->status_lead = 'Declined';
                $appointment->inspection_status = 'Declined';
                $appointment->notes = $appointment->notes . "\n\n" . date('Y-m-d H:i:s') . " - Notificación de rechazo enviada. Razones: " 
                    . ($this->noContact ? "No fue posible contactar. " : "")
                    . ($this->noInsurance ? "No tiene seguro de propiedad. " : "")
                    . ($this->otherReason ? "Otro: {$this->otherReason}" : "");
                $appointment->save();
                
                Log::info("Notificación de rechazo enviada con éxito para la cita UUID: {$appointmentId}");
            } catch (\Exception $e) {
                Log::error("Error al enviar notificación de rechazo para la cita UUID: {$appointmentId} - Error: {$e->getMessage()}");
                // Continuar procesando otras citas aunque una falle
            }
        }
    }
} 
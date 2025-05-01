<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;
use App\Jobs\ProcessAppointmentEmail;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-appointment-reminders {--days=1 : Número de días antes de la cita para enviar el recordatorio}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía recordatorios por correo electrónico para las citas programadas próximamente';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daysBeforeAppointment = $this->option('days');
        $reminderDate = Carbon::tomorrow()->addDays($daysBeforeAppointment - 1);
        
        $this->info("Enviando recordatorios para citas programadas para el " . $reminderDate->format('Y-m-d'));

        // Obtenemos las citas confirmadas para la fecha objetivo
        $appointments = Appointment::where('inspection_date', $reminderDate->format('Y-m-d'))
            ->where('inspection_confirmed', true)
            ->whereNotIn('inspection_status', ['Declined', 'Completed'])
            ->get();

        $count = 0;
        $errors = 0;

        foreach ($appointments as $appointment) {
            try {
                // Crear job para enviar el recordatorio
                ProcessAppointmentEmail::dispatch($appointment, 'reminder');
                
                $count++;
                $this->info("Job de recordatorio creado para: {$appointment->email}");
                
                // Opcionalmente, podemos actualizar el registro para marcar que se envió el recordatorio
                $appointment->timestamps = false; // Evita actualizar updated_at
                $appointment->reminder_sent = true;
                $appointment->save();
                
            } catch (\Exception $e) {
                $errors++;
                $this->error("Error al crear job de recordatorio para {$appointment->email}: {$e->getMessage()}");
                Log::error("Error al enviar recordatorio de cita: " . $e->getMessage(), [
                    'appointment_id' => $appointment->id,
                    'email' => $appointment->email
                ]);
            }
        }

        $this->info("Proceso completado: {$count} recordatorios programados, {$errors} errores.");
        
        if ($count === 0 && $errors === 0) {
            $this->info("No se encontraron citas programadas para el " . $reminderDate->format('Y-m-d'));
        }

        return 0;
    }
}

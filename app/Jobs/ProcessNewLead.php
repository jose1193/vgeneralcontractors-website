<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;
use App\Models\EmailData;
use App\Notifications\NewLeadNotification;
use Illuminate\Support\Facades\Notification;

class ProcessNewLead implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function handle()
    {
        // Get the appointment email from EmailData
        $appointmentEmail = EmailData::where('type', 'appointment')->first();
        
        if ($appointmentEmail) {
            Notification::route('mail', $appointmentEmail->email)
                ->notify(new NewLeadNotification($this->appointment));
        }
    }
} 
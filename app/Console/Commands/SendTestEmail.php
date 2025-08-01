<?php

namespace App\Console\Commands;

use App\Mail\TestMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to verify the mail configuration';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Sending test email to {$email}...");
        
        try {
            Mail::to($email)->send(new TestMail());
            $this->info("Test email sent successfully!");
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to send test email: " . $e->getMessage());
            $this->line("Exception trace: " . $e->getTraceAsString());
            return 1;
        }
    }
}

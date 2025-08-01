<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\EmailData;
use App\Mail\TestMail;

class MailDebugController extends Controller
{
    public function testMail(Request $request)
    {
        try {
            // Test configuration
            $config = config('mail');
            $mailer = config('mail.default');
            $adminEmail = EmailData::where('type', 'Admin')->first();
            
            // Create debug test mail class
            if (!class_exists('App\\Mail\\TestMail')) {
                file_put_contents(
                    app_path('Mail/TestMail.php'),
                    '<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function build()
    {
        return $this->subject("Test Mail")
                    ->html("<h1>Mail System Test</h1><p>This is a test email to verify the mail system is working.</p>");
    }
}'
                );
            }
            
            // Send test email directly (not through queue)
            $email = $request->input('email') ?: ($adminEmail ? $adminEmail->email : 'admin@example.com');
            Mail::to($email)->send(new TestMail());
            
            // Check queue status
            $queueConfig = config('queue');
            $queueConnection = config('queue.default');
            $hasFailedJobs = \DB::table('failed_jobs')->count() > 0;
            $recentFailedJobs = \DB::table('failed_jobs')
                ->orderBy('failed_at', 'desc')
                ->limit(5)
                ->get();
            
            // Log success
            Log::info('Test mail sent successfully', [
                'email' => $email,
                'mailer' => $mailer,
                'mail_config' => $config,
                'queue_connection' => $queueConnection,
                'queue_config' => $queueConfig,
                'has_failed_jobs' => $hasFailedJobs
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Test mail sent successfully',
                'email' => $email,
                'mailer' => $mailer,
                'mail_config' => [
                    'default' => $config['default'],
                    'mailers' => array_keys($config['mailers']),
                    'from' => $config['from'] ?? null,
                ],
                'queue_connection' => $queueConnection,
                'queue_config' => [
                    'default' => $queueConfig['default'],
                    'connections' => array_keys($queueConfig['connections']),
                ],
                'admin_email' => $adminEmail ? $adminEmail->email : null,
                'has_failed_jobs' => $hasFailedJobs,
                'recent_failed_jobs' => $recentFailedJobs,
            ]);
        } catch (\Exception $e) {
            Log::error('Test mail error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Test mail error: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => explode("\n", $e->getTraceAsString()),
            ], 500);
        }
    }
}

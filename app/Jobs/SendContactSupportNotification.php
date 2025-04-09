<?php // MUST be the very first thing in the file. No spaces or lines before this.

namespace App\Jobs;

use App\Mail\ContactSupportNotification as ContactSupportNotificationMail; // Alias for clarity
use App\Models\CompanyData;
use App\Models\ContactSupport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable; // Make sure this is imported

class SendContactSupportNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The contact support instance.
     *
     * @var \App\Models\ContactSupport
     */
    public ContactSupport $contactSupport; // Added type hint (optional, requires PHP 7.4+)

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3; // Example: Allow 3 attempts

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60; // Example: Wait 60 seconds before retry

    /**
     * Create a new job instance.
     *
     * @param \App\Models\ContactSupport $contactSupport
     * @return void
     */
    public function __construct(ContactSupport $contactSupport)
    {
        // Use `withoutRelations()` if you don't need related models in the job
        // to potentially reduce payload size, but only if you're sure you won't access them later.
        $this->contactSupport = $contactSupport->withoutRelations();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void // Added return type hint (optional, requires PHP 7.1+)
    {
        try {
            // Find the company data to get the recipient email
            // Assuming there's usually one primary company data record.
            // Cache this lookup if it's frequently accessed and doesn't change often.
            $companyData = CompanyData::query()
                // Add any conditions if needed, e.g., ->where('is_primary', true)
                ->first();

            if (!$companyData || !$companyData->email) {
                Log::warning('SendContactSupportNotification Job: Company email not found or not set in CompanyData. Cannot send notification.', [
                    'contact_support_id' => $this->contactSupport->id,
                    'job_id' => $this->job?->getJobId() // Log job ID if available
                ]);
                // You might want to fail the job explicitly if this is critical
                // $this->fail('Company email not configured.');
                return; // Stop processing if no email address
            }

            $recipientEmail = $companyData->email;

            // Check if recipient email is valid format (basic check)
            if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
                 Log::error('SendContactSupportNotification Job: Invalid recipient email format in CompanyData.', [
                    'contact_support_id' => $this->contactSupport->id,
                    'job_id' => $this->job?->getJobId(),
                    'invalid_email' => $recipientEmail
                 ]);
                 $this->fail('Invalid recipient email format configured.'); // Fail the job
                 return;
            }

            // Create the Mailable instance using the alias
            $email = new ContactSupportNotificationMail($this->contactSupport);

            // Send the email
            Mail::to($recipientEmail)->send($email);

            Log::info('Contact support notification email sent successfully.', [
                'contact_support_id' => $this->contactSupport->id,
                'recipient' => $recipientEmail,
                'job_id' => $this->job?->getJobId()
            ]);

        } catch (Throwable $e) {
            Log::error('Failed to send contact support notification email.', [
                'contact_support_id' => $this->contactSupport->id,
                'job_id' => $this->job?->getJobId(),
                'error_message' => $e->getMessage(),
                // Only include trace in detailed logs or non-production environments if needed
                // 'trace' => $e->getTraceAsString()
            ]);

            // Check if the job has attempts remaining before releasing back to the queue
            if ($this->attempts() < $this->tries) {
                // Release the job back onto the queue with a delay (backoff)
                $this->release($this->backoff);
            } else {
                // Max attempts reached, fail the job permanently
                $this->fail($e);
                // Optional: Send a notification to an admin about the permanent failure
            }
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception): void
    {
        // This method is called when the job fails permanently (after all retries)
        Log::critical('SendContactSupportNotification Job FAILED permanently.', [
            'contact_support_id' => $this->contactSupport->id,
            'job_id' => $this->job?->getJobId(),
            'error_message' => $exception->getMessage(),
        ]);
        // Send notification to admin channel (e.g., Slack, email)
        // AdminNotification::send('Contact Support Email Job Failed for ID: ' . $this->contactSupport->id);
    }
}
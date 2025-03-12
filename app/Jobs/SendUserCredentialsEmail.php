<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCredentialsMail;
use App\Mail\PasswordResetMail;

class SendUserCredentialsEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $password;
    protected $isPasswordReset;

    public function __construct(User $user, ?string $password = null, bool $isPasswordReset = false)
    {
        $this->user = $user;
        $this->password = $password;
        $this->isPasswordReset = $isPasswordReset;
    }

    public function handle(): void
    {
        $mailable = $this->isPasswordReset 
            ? new PasswordResetMail($this->user, $this->password)
            : new UserCredentialsMail($this->user, $this->password);

        Mail::to($this->user->email)->send($mailable);
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Jobs\SendUserCredentialsEmail;
use Illuminate\Support\Facades\Log;

class TestUserEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:test-email {user_id} {--password=testpass123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test sending user credentials email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $password = $this->option('password');
        
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }
        
        $this->info("Testing email for user: {$user->name} ({$user->email})");
        
        try {
            Log::info('Testing user email from console command', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'test_password' => $password
            ]);
            
            // Test with both patterns
            $this->info("Testing with static dispatch...");
            SendUserCredentialsEmail::dispatch($user, $password, false);
            
            $this->info("Testing with helper dispatch...");
            dispatch(new SendUserCredentialsEmail($user, $password, true));
            
            $this->info("Both email jobs dispatched successfully!");
            $this->info("Check logs for more details: php artisan queue:work");
            
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to dispatch email: " . $e->getMessage());
            Log::error('Test email command failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
} 
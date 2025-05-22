<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixStoragePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:fix-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix permissions for storage directory and log files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing storage directory permissions...');

        // Get current user/group to restore afterward
        $user = trim(shell_exec('whoami'));
        $this->info("Current user: {$user}");

        try {
            // Storage directory permissions
            $this->executeCommand('chmod -R 775 storage');
            $this->executeCommand('chmod -R g+s storage');  // Set SGID bit to maintain group ownership
            
            // Create logs directory if it doesn't exist
            if (!is_dir(storage_path('logs'))) {
                $this->executeCommand('mkdir -p storage/logs');
            }
            
            // Touch log files to ensure they exist
            $this->executeCommand('touch storage/logs/laravel.log');
            $this->executeCommand('touch storage/logs/scheduler.log');
            $this->executeCommand('touch storage/logs/calls.log');
            $this->executeCommand('touch storage/logs/appointments.log');
            $this->executeCommand('touch storage/logs/sitemap.log');
            $this->executeCommand('touch storage/logs/scheduled-posts.log');
            
            // Set more permissive permissions for log files
            $this->executeCommand('chmod 664 storage/logs/*.log');
            
            // Make bootstrap/cache writable
            $this->executeCommand('chmod -R 775 bootstrap/cache');
            
            $this->info('Permissions fixed successfully!');
            
        } catch (\Exception $e) {
            $this->error('Error fixing permissions: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
    
    /**
     * Execute a shell command
     */
    private function executeCommand($command)
    {
        $this->info("Executing: {$command}");
        $result = shell_exec($command);
        if ($result) {
            $this->line($result);
        }
    }
} 
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class VerifyAdminTechnician extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:verify-staff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify all admin and teknisi users (they do not need email verification)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verifying admin and teknisi users...');
        
        $updated = User::whereIn('role', ['admin', 'teknisi'])
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);
        
        if ($updated > 0) {
            $this->info("✓ Successfully verified {$updated} staff user(s).");
        } else {
            $this->info('✓ All admin and teknisi users are already verified.');
        }
        
        // Display all staff users status
        $this->newLine();
        $this->info('Current staff users:');
        $this->table(
            ['Username', 'Role', 'Email', 'Verified'],
            User::whereIn('role', ['admin', 'teknisi'])
                ->get(['username', 'role', 'email', 'email_verified_at'])
                ->map(function ($user) {
                    return [
                        $user->username,
                        $user->role,
                        $user->email,
                        $user->email_verified_at ? '✓ Yes' : '✗ No'
                    ];
                })
        );
        
        return Command::SUCCESS;
    }
}

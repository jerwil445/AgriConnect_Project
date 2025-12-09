<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ShowUserCredentials extends Command
{
    protected $signature = 'users:show-credentials {--reset-password= : Reset all passwords to this value}';
    protected $description = 'Show all user credentials and optionally reset passwords';

    public function handle()
    {
        $users = DB::table('users')
            ->select('id', 'first_name', 'last_name', 'email', 'role')
            ->orderBy('role')
            ->orderBy('id')
            ->get();

        if ($users->isEmpty()) {
            $this->error('No users found in database.');
            return 1;
        }

        $this->info('Current User Accounts:');
        $this->newLine();

        $tableData = [];
        foreach ($users as $user) {
            $tableData[] = [
                $user->id,
                trim($user->first_name . ' ' . $user->last_name),
                $user->email,
                ucfirst($user->role),
                '********' // Password is hashed
            ];
        }

        $this->table(
            ['ID', 'Name', 'Email', 'Role', 'Password'],
            $tableData
        );

        $this->newLine();
        $this->warn('Note: Passwords are hashed in the database for security.');
        $this->info('The actual passwords depend on how the accounts were created.');
        $this->newLine();
        
        // Check if we should reset passwords
        if ($newPassword = $this->option('reset-password')) {
            if ($this->confirm("Reset all user passwords to '{$newPassword}'?")) {
                $hashedPassword = Hash::make($newPassword);
                
                foreach ($users as $user) {
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['password' => $hashedPassword]);
                }
                
                $this->newLine();
                $this->info("✓ All passwords have been reset to: {$newPassword}");
                $this->newLine();
                
                // Show updated table
                $tableData = [];
                foreach ($users as $user) {
                    $tableData[] = [
                        $user->id,
                        trim($user->first_name . ' ' . $user->last_name),
                        $user->email,
                        ucfirst($user->role),
                        $newPassword
                    ];
                }
                
                $this->table(
                    ['ID', 'Name', 'Email', 'Role', 'Password'],
                    $tableData
                );
            }
        } else {
            $this->info('Common default passwords to try:');
            $this->line('  • password');
            $this->line('  • 12345678');
            $this->line('  • password123');
            $this->newLine();
            $this->info('To reset all passwords, run:');
            $this->line('  php artisan users:show-credentials --reset-password=newpassword');
        }

        return 0;
    }
}

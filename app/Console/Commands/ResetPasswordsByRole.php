<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordsByRole extends Command
{
    protected $signature = 'users:reset-by-role {--force : Force without confirmation} {--create-admin : Create admin account if missing}';
    protected $description = 'Reset passwords based on user role (farmers=farmer, buyers=buyer, admin=admin)';

    public function handle()
    {
        // Check if admin exists
        $adminExists = DB::table('users')->where('role', 'admin')->exists();
        
        if (!$adminExists && $this->option('create-admin')) {
            $this->info('Creating admin account...');
            
            DB::table('users')->insert([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@agriconnect.com',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->info('✓ Admin account created: admin@agriconnect.com');
            $this->newLine();
        } elseif (!$adminExists) {
            $this->warn('No admin account found. Run with --create-admin to create one.');
            $this->newLine();
        }
        
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
            $newPassword = strtolower($user->role); // farmer, buyer, admin
            $tableData[] = [
                $user->id,
                trim($user->first_name . ' ' . $user->last_name),
                $user->email,
                ucfirst($user->role),
                $newPassword
            ];
        }

        $this->table(
            ['ID', 'Name', 'Email', 'Role', 'New Password'],
            $tableData
        );

        $this->newLine();

        if (!$this->option('force')) {
            if (!$this->confirm('Reset passwords as shown above?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('Resetting passwords...');

        foreach ($users as $user) {
            $newPassword = strtolower($user->role);
            $hashedPassword = Hash::make($newPassword);
            
            DB::table('users')
                ->where('id', $user->id)
                ->update(['password' => $hashedPassword]);
                
            $this->info("✓ {$user->email} → password: {$newPassword}");
        }

        $this->newLine();
        $this->info('✓ All passwords have been reset successfully!');
        $this->newLine();
        
        // Show final credentials
        $this->info('Login Credentials:');
        $this->table(
            ['Email', 'Password', 'Role'],
            $users->map(function($user) {
                return [
                    $user->email,
                    strtolower($user->role),
                    ucfirst($user->role)
                ];
            })
        );

        return 0;
    }
}

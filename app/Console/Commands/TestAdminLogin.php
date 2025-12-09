<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TestAdminLogin extends Command
{
    protected $signature = 'test:admin-login';
    protected $description = 'Test admin login credentials';

    public function handle()
    {
        $email = 'admin@agriconnect.com';
        $password = 'admin';
        
        $this->info("Testing admin login...");
        $this->info("Email: {$email}");
        $this->info("Password: {$password}");
        $this->newLine();
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error('❌ Admin user not found in database!');
            return 1;
        }
        
        $this->info("✓ User found");
        $this->info("  - ID: {$user->id}");
        $this->info("  - Name: {$user->first_name} {$user->last_name}");
        $this->info("  - Email: {$user->email}");
        $this->info("  - Role: {$user->role}");
        $this->newLine();
        
        // Test password
        if (Hash::check($password, $user->password)) {
            $this->info("✓ Password is correct!");
        } else {
            $this->error("❌ Password does not match!");
            $this->warn("Resetting password to 'admin'...");
            $user->password = Hash::make('admin');
            $user->save();
            $this->info("✓ Password has been reset");
        }
        
        $this->newLine();
        $this->info("Login credentials:");
        $this->line("  Email: {$email}");
        $this->line("  Password: {$password}");
        
        return 0;
    }
}

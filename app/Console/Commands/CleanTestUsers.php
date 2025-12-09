<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanTestUsers extends Command
{
    protected $signature = 'users:clean-test {--force : Force the operation without confirmation}';
    protected $description = 'Remove all test users (example.com, example.org, example.net emails) and keep only real accounts';

    public function handle()
    {
        // Show current user counts
        $totalUsers = DB::table('users')->count();
        $testUsers = DB::table('users')
            ->where('email', 'like', '%@example.com')
            ->orWhere('email', 'like', '%@example.org')
            ->orWhere('email', 'like', '%@example.net')
            ->count();
        $realUsers = $totalUsers - $testUsers;

        $this->info("Current database status:");
        $this->info("  Total users: {$totalUsers}");
        $this->info("  Test users: {$testUsers}");
        $this->info("  Real users: {$realUsers}");
        $this->newLine();

        // Show real users before deletion
        $this->info('Real user accounts (will be kept):');
        $realUsersList = DB::table('users')
            ->select('id', 'first_name', 'last_name', 'email', 'role')
            ->where('email', 'not like', '%@example.com')
            ->where('email', 'not like', '%@example.org')
            ->where('email', 'not like', '%@example.net')
            ->orderBy('role')
            ->get();
        
        if ($realUsersList->isEmpty()) {
            $this->error('No real users found! All users have test emails.');
            $this->warn('Please create at least one admin account before running this command.');
            return 1;
        }

        $this->table(
            ['ID', 'Name', 'Email', 'Role'],
            $realUsersList->map(function ($user) {
                return [
                    $user->id,
                    trim($user->first_name . ' ' . $user->last_name),
                    $user->email,
                    $user->role
                ];
            })
        );

        $this->newLine();

        if (!$this->option('force')) {
            if (!$this->confirm("This will delete {$testUsers} test users. Continue?")) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('Removing test users...');

        // Get test user IDs
        $testUserIds = DB::table('users')
            ->where('email', 'like', '%@example.com')
            ->orWhere('email', 'like', '%@example.org')
            ->orWhere('email', 'like', '%@example.net')
            ->pluck('id');

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // Delete related farmer profiles
        $deletedFarmers = DB::table('farmers')->whereIn('user_id', $testUserIds)->delete();
        $this->info("✓ Deleted {$deletedFarmers} test farmer profiles");

        // Delete related buyer profiles
        $deletedBuyers = DB::table('buyers')->whereIn('user_id', $testUserIds)->delete();
        $this->info("✓ Deleted {$deletedBuyers} test buyer profiles");

        // Delete test users
        $deletedUsers = DB::table('users')
            ->where('email', 'like', '%@example.com')
            ->orWhere('email', 'like', '%@example.org')
            ->orWhere('email', 'like', '%@example.net')
            ->delete();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $this->newLine();
        $this->info("✓ Successfully deleted {$deletedUsers} test users");
        
        // Show final user count
        $finalCount = DB::table('users')->count();
        $this->info("✓ Remaining users: {$finalCount}");

        return 0;
    }
}

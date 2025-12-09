<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:clean {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean all transactional data from the database while preserving user accounts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('This will delete ALL transactional data (products, orders, demands, messages, etc.) but keep user accounts. Do you wish to continue?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('Starting database cleanup...');

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $tables = [
            'notifications',
            'messages',
            'conversations',
            'size_transactions',
            'transactions',
            'product_images',
            'sizes',
            'farmer_earnings',
            'farmer_reviews',
            'inventory_logs',
            'product_analytics',
            'demand_matches',  // Must be before demands
            'demands',
            'products',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->info("✓ Cleared table: {$table}");
            } else {
                $this->warn("✗ Table not found: {$table}");
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $this->newLine();
        $this->info('Database cleanup completed successfully!');
        
        // Show remaining users
        $this->newLine();
        $this->info('Remaining user accounts:');
        $users = DB::table('users')
            ->select('id', 'first_name', 'last_name', 'email', 'role')
            ->orderBy('role')
            ->get();
        
        $this->table(
            ['ID', 'Name', 'Email', 'Role'],
            $users->map(function ($user) {
                return [
                    $user->id, 
                    trim($user->first_name . ' ' . $user->last_name), 
                    $user->email, 
                    $user->role
                ];
            })
        );

        return 0;
    }
}

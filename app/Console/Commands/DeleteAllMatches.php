<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeleteAllMatches extends Command
{
    protected $signature = 'matches:delete-all {--force : Force the operation without confirmation}';
    protected $description = 'Delete all demand matches from the database';

    public function handle()
    {
        // Check if table exists
        if (!Schema::hasTable('matches')) {
            $this->error('Table "matches" does not exist in the database.');
            return 1;
        }

        // Count existing matches
        $matchCount = DB::table('matches')->count();
        
        $this->info("Current matches in database: {$matchCount}");
        
        if ($matchCount === 0) {
            $this->info('No matches to delete.');
            return 0;
        }

        if (!$this->option('force')) {
            if (!$this->confirm("This will permanently delete all {$matchCount} demand matches. Continue?")) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('Deleting all matches...');

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // Delete all matches
        DB::table('matches')->delete();

        // Reset auto-increment
        DB::statement('ALTER TABLE matches AUTO_INCREMENT = 1');

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $this->newLine();
        $this->info("✓ Successfully deleted {$matchCount} matches");
        
        // Verify deletion
        $remainingCount = DB::table('matches')->count();
        $this->info("✓ Remaining matches: {$remainingCount}");

        return 0;
    }
}

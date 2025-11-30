<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Size;

class CheckSizesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-sizes-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the sizes data in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sizes = Size::all();
        
        $this->info('Total sizes in database: ' . $sizes->count());
        
        foreach ($sizes as $size) {
            $this->line("ID: {$size->id}, Size: {$size->size_name}, Trays: {$size->tray_count}, Price per tray: {$size->price_per_tray}, Total price: {$size->total_price}");
        }
    }
}
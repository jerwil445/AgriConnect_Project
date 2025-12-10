<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Buyer;

class CheckSeededData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:seeded-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the seeded farmers and buyers data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== DATABASE VERIFICATION ===');
        $this->line('Total Users: ' . User::count());
        $this->line('Farmers: ' . User::where('role', 'farmer')->count());
        $this->line('Buyers: ' . User::where('role', 'buyer')->count());
        $this->line('Farmer Profiles: ' . Farmer::count());
        $this->line('Buyer Profiles: ' . Buyer::count());
        $this->newLine();

        $this->info('=== SAMPLE FARMER DATA ===');
        $farmer = Farmer::with('user')->first();
        if ($farmer) {
            $this->line('Name: ' . $farmer->user->first_name . ' ' . $farmer->user->last_name);
            $this->line('Email: ' . $farmer->user->email);
            $this->line('Farm: ' . $farmer->farm_name);
            $this->line('Location: ' . $farmer->farm_address);
            $this->line('Phone: ' . $farmer->user->phone_number);
            $this->line('Farm Size: ' . $farmer->farm_size . ' ' . $farmer->farm_size_unit);
            $this->line('Experience: ' . $farmer->experience_years . ' years');
            $this->line('Certification: ' . $farmer->certification);
            $this->line('Business Type: ' . $farmer->business_type);
            $this->line('Bank: ' . $farmer->bank_name);
            $this->line('Total Chickens: ' . $farmer->total_chickens);
            $this->line('Farming Method: ' . $farmer->farming_method);
        }

        $this->newLine();
        $this->info('=== SAMPLE BUYER DATA ===');
        $buyer = Buyer::with('user')->first();
        if ($buyer) {
            $this->line('Name: ' . $buyer->user->first_name . ' ' . $buyer->user->last_name);
            $this->line('Email: ' . $buyer->user->email);
            $this->line('Company: ' . $buyer->company_name);
            $this->line('Business Type: ' . $buyer->business_type);
            $this->line('Address: ' . $buyer->address);
            $this->line('Phone: ' . $buyer->user->phone_number);
            $this->line('Preferred Products: ' . $buyer->preferred_products);
            $this->line('Verified: ' . ($buyer->verified ? 'Yes' : 'No'));
        }

        $this->newLine();
        $this->info('=== VERIFICATION STATUS BREAKDOWN ===');
        $this->line('Verified Farmers: ' . User::where('role', 'farmer')->where('kyc_status', 'verified')->count());
        $this->line('Pending Farmers: ' . User::where('role', 'farmer')->where('kyc_status', 'pending')->count());
        $this->line('Verified Buyers: ' . User::where('role', 'buyer')->where('kyc_status', 'verified')->count());
        $this->line('Pending Buyers: ' . User::where('role', 'buyer')->where('kyc_status', 'pending')->count());

        $this->newLine();
        $this->info('=== RECENT ENTRIES ===');
        $recentFarmers = User::where('role', 'farmer')->latest()->take(3)->get();
        $this->line('Last 3 Farmers Created:');
        foreach ($recentFarmers as $farmer) {
            $this->line('- ' . $farmer->first_name . ' ' . $farmer->last_name . ' (' . $farmer->email . ')');
        }

        $recentBuyers = User::where('role', 'buyer')->latest()->take(3)->get();
        $this->newLine();
        $this->line('Last 3 Buyers Created:');
        foreach ($recentBuyers as $buyer) {
            $this->line('- ' . $buyer->first_name . ' ' . $buyer->last_name . ' (' . $buyer->email . ')');
        }

        $this->newLine();
        $this->success('Data seeding verification completed successfully!');
    }
}

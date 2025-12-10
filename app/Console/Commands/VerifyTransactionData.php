<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\User;

class VerifyTransactionData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify the seeded transaction data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== TRANSACTION DATABASE VERIFICATION ===');
        
        // Basic counts
        $totalTransactions = Transaction::count();
        $this->line("Total Transactions: $totalTransactions");
        
        // Status distribution
        $this->newLine();
        $this->info('=== STATUS DISTRIBUTION ===');
        $statusCounts = Transaction::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->orderByDesc('count')
            ->get();
            
        foreach ($statusCounts as $status) {
            $percentage = round(($status->count / $totalTransactions) * 100, 1);
            $this->line("{$status->status}: {$status->count} ({$percentage}%)");
        }

        // Payment status distribution
        $this->newLine();
        $this->info('=== PAYMENT STATUS DISTRIBUTION ===');
        $paymentCounts = Transaction::selectRaw('payment_status, COUNT(*) as count')
            ->groupBy('payment_status')
            ->orderByDesc('count')
            ->get();
            
        foreach ($paymentCounts as $payment) {
            $percentage = round(($payment->count / $totalTransactions) * 100, 1);
            $this->line("{$payment->payment_status}: {$payment->count} ({$percentage}%)");
        }

        // Payment method distribution
        $this->newLine();
        $this->info('=== PAYMENT METHOD DISTRIBUTION ===');
        $methodCounts = Transaction::selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->orderByDesc('count')
            ->get();
            
        foreach ($methodCounts as $method) {
            $percentage = round(($method->count / $totalTransactions) * 100, 1);
            $this->line("{$method->payment_method}: {$method->count} ({$percentage}%)");
        }

        // Transaction value statistics
        $this->newLine();
        $this->info('=== TRANSACTION VALUE STATISTICS ===');
        $stats = Transaction::selectRaw('
            MIN(total_amount) as min_amount,
            MAX(total_amount) as max_amount,
            AVG(total_amount) as avg_amount,
            SUM(total_amount) as total_value
        ')->first();

        $this->line("Minimum Amount: ₱" . number_format($stats->min_amount, 2));
        $this->line("Maximum Amount: ₱" . number_format($stats->max_amount, 2));
        $this->line("Average Amount: ₱" . number_format($stats->avg_amount, 2));
        $this->line("Total Value: ₱" . number_format($stats->total_value, 2));

        // Date range
        $this->newLine();
        $this->info('=== TRANSACTION DATE RANGE ===');
        $dateRange = Transaction::selectRaw('
            MIN(created_at) as earliest,
            MAX(created_at) as latest
        ')->first();

        $this->line("Earliest Transaction: " . $dateRange->earliest);
        $this->line("Latest Transaction: " . $dateRange->latest);

        // Sample transaction details
        $this->newLine();
        $this->info('=== SAMPLE TRANSACTIONS ===');
        $sampleTransactions = Transaction::with(['buyer', 'farmer'])->take(3)->get();
        
        foreach ($sampleTransactions as $transaction) {
            $this->line("Transaction ID: {$transaction->id}");
            $this->line("  Buyer: {$transaction->buyer_name} ({$transaction->buyer_email})");
            $this->line("  Farmer: {$transaction->farmer->first_name} {$transaction->farmer->last_name}");
            $this->line("  Amount: ₱" . number_format($transaction->total_amount, 2));
            $this->line("  Status: {$transaction->status}");
            $this->line("  Payment: {$transaction->payment_status} via {$transaction->payment_method}");
            $this->line("  Date: {$transaction->created_at->format('Y-m-d H:i:s')}");
            
            if ($transaction->size_details) {
                $sizeDetails = json_decode($transaction->size_details, true);
                if (isset($sizeDetails['egg_type']) && isset($sizeDetails['size']) && isset($sizeDetails['trays'])) {
                    $this->line("  Product: {$sizeDetails['egg_type']} ({$sizeDetails['size']}) - {$sizeDetails['trays']} trays");
                }
            }
            
            $this->line("---");
        }

        // Farmer participation
        $this->newLine();
        $this->info('=== FARMER PARTICIPATION ===');
        $farmerStats = Transaction::selectRaw('
            COUNT(DISTINCT farmer_id) as unique_farmers,
            COUNT(*) / COUNT(DISTINCT farmer_id) as avg_transactions_per_farmer
        ')->first();

        $totalFarmers = User::where('role', 'farmer')->count();
        $participationRate = round(($farmerStats->unique_farmers / $totalFarmers) * 100, 1);

        $this->line("Farmers with transactions: {$farmerStats->unique_farmers} out of {$totalFarmers} ({$participationRate}%)");
        $this->line("Average transactions per active farmer: " . round($farmerStats->avg_transactions_per_farmer, 1));

        // Buyer participation
        $this->newLine();
        $this->info('=== BUYER PARTICIPATION ===');
        $buyerStats = Transaction::selectRaw('
            COUNT(DISTINCT buyer_id) as unique_buyers,
            COUNT(*) / COUNT(DISTINCT buyer_id) as avg_transactions_per_buyer
        ')->first();

        $totalBuyers = User::where('role', 'buyer')->count();
        $buyerParticipationRate = round(($buyerStats->unique_buyers / $totalBuyers) * 100, 1);

        $this->line("Buyers with transactions: {$buyerStats->unique_buyers} out of {$totalBuyers} ({$buyerParticipationRate}%)");
        $this->line("Average transactions per active buyer: " . round($buyerStats->avg_transactions_per_buyer, 1));

        $this->newLine();
        $this->line('Transaction data verification completed successfully!');
    }
}

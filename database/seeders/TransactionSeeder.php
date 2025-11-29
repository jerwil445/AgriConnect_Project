<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing transactions to set initiator_id
        // For existing transactions, we'll set the buyer as the initiator by default
        Transaction::whereNull('initiator_id')->each(function ($transaction) {
            $transaction->update(['initiator_id' => $transaction->buyer_id]);
        });
    }
}
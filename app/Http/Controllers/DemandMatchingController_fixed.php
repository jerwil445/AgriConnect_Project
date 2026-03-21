<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\ConversationThread;
use App\Models\Demand;
use App\Models\DemandMatch;
use App\Models\Farmer;
use App\Models\Message;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\BuyerMatchNotification;
use App\Notifications\FarmerMatchNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DemandMatchingController extends Controller
{
    // ... (previous methods omitted for brevity)

    public function placeOrder(Request $request, Transaction $transaction)
    {
        // Check if the authenticated user is the buyer in this transaction
        if (Auth::id() != $transaction->buyer_id) {
            return back()->with('error', 'Unauthorized access.');
        }

        // Validate the request data
        $validatedData = $request->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'required|email|max:255',
            'buyer_phone' => 'required|string|max:20',
            'buyer_address' => 'required|string|max:500',
            'payment_method' => 'required|string|in:cash_on_delivery,bank_transfer,credit_card,e_wallet',
            'order_quantity' => 'nullable|integer|min:1',
        ]);

        // Determine the ordered quantity
        // If order_quantity is provided, use that, otherwise fall back to demand quantity or product quantity
        $orderedQuantity = $validatedData['order_quantity'] ??
            ($transaction->demand ? $transaction->demand->quantity : $transaction->product->quantity);

        // Validate ordered quantity
        if (!is_numeric($orderedQuantity) || $orderedQuantity <= 0) {
            return back()->with('error', 'Invalid order quantity.');
        }

        // Update transaction with order details
        $transaction->update([
            'buyer_name' => $validatedData['buyer_name'],
            'buyer_email' => $validatedData['buyer_email'],
            'buyer_phone' => $validatedData['buyer_phone'],
            'buyer_address' => $validatedData['buyer_address'],
            'payment_method' => $validatedData['payment_method'],
            'status' => 'Ordered',
            'final_quantity' => $orderedQuantity,
            'final_price' => $transaction->product->price,
            'total_amount' => $orderedQuantity * $transaction->product->price
        ]);

        // Deduct the ordered quantity from the product
        $product = $transaction->product;

        // Check if ordered quantity exceeds available quantity
        if ($orderedQuantity > $product->quantity) {
            return back()->with('error', 'You cannot order more than the available quantity of ' . $product->quantity . ' ' . $product->unit . '.');
        }

        // Check if there's enough quantity available
        if ($product->quantity >= $orderedQuantity) {
            // Deduct the quantity
            $newQuantity = $product->quantity - $orderedQuantity;
            $product->update([
                'quantity' => $newQuantity
            ]);

            // If the product quantity reaches 0, mark it as sold out
            if ($newQuantity <= 0) {
                $product->update([
                    'status' => 'Sold Out'
                ]);
            }

            // Update the "Is this available?" message with the new quantity
            $this->updateAvailabilityMessage($transaction, $newQuantity);
        }
        else {
            // Not enough quantity available, rollback the order
            $transaction->update([
                'status' => 'Active' // Reset status
            ]);

            return back()->with('error', 'Not enough quantity available for this product. Only ' . $product->quantity . ' ' . $product->unit . ' remaining. You cannot place any more orders for this product as it is now sold out.');
        }

        // Update the match status to 'Ordered' (only if there's a demand)
        if ($transaction->demand_id) {
            $demandMatch = DemandMatch::where('demand_id', $transaction->demand_id)
                ->where('product_id', $transaction->product_id)
                ->first();

            if ($demandMatch) {
                $demandMatch->load(['demand', 'product']); // Load relationships for the match
                $demandMatch->update([
                    'status' => 'Ordered'
                ]);
            }
        }

        // Send notification to farmer about the order
        $farmerUser = $transaction->farmer;
        if ($farmerUser && $transaction->product) {
            $productName = $transaction->product->product_name;
            $message = "A buyer has placed an order for your product \"{$productName}\". Please check the order details.";
            $data = [
                'message' => $message,
                'transaction_id' => $transaction->id,
                'product_name' => $productName,
                'quantity' => $orderedQuantity,
                'total_amount' => $orderedQuantity * $transaction->final_price
            ];
            $farmerUser->notify(new FarmerMatchNotification($data));
        }

        // Send notification to buyer about their order
        $buyerUser = $transaction->buyer;
        if ($buyerUser && $transaction->product) {
            $productName = $transaction->product->product_name;
            $message = "You have placed an order for \"{$productName}\". The farmer will review your order shortly.";
            $data = [
                'message' => $message,
                'transaction_id' => $transaction->id,
                'product_name' => $productName,
                'quantity' => $orderedQuantity,
                'total_amount' => $orderedQuantity * $transaction->final_price
            ];
            $buyerUser->notify(new \App\Notifications\BuyerFarmerAcceptNotification($data));
        }

        return redirect()->back()->with('success', 'Order placed successfully! You can place another order for this product as long as there is quantity available. ' . $newQuantity . ' ' . $product->unit . ' remaining.');
    }

// ... (remaining methods omitted for brevity)
}
<?php

namespace App\Http\Controllers;

use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    /**
     * Store a newly created verification document in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        $user = auth()->user();

        // Check if user already has an approved document of this type
        $existing = Verification::where('user_id', $user->id)
            ->where('document_type', $request->document_type)
            ->where('status', 'approved')
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have an approved ' . $request->document_type . '.');
        }

        $path = $request->file('file')->store('verifications/' . $user->id, 'public');

        Verification::create([
            'user_id' => $user->id,
            'document_type' => $request->document_type,
            'file_path' => $path,
            'status' => 'pending',
        ]);

        // Update user KYC status to pending if it's not already verified
        if ($user->kyc_status !== 'verified') {
            $user->update(['kyc_status' => 'pending']);
        }

        return back()->with('success', 'Document uploaded successfully and is pending review.');
    }

    /**
     * Remove the specified verification document from storage.
     */
    public function destroy(Verification $verification)
    {
        // Only allow deleting if it's the user's own document and it's not approved
        if ($verification->user_id !== auth()->id() || $verification->status === 'approved') {
            return back()->with('error', 'You cannot delete this document.');
        }

        Storage::disk('public')->delete($verification->file_path);
        $verification->delete();

        return back()->with('success', 'Document removed successfully.');
    }
}

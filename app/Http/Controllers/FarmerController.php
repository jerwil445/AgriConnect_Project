<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class FarmerController extends Controller
{
    /**
     * Display the farmer profile.
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('farmers.profile', compact('user'));
    }

    /**
     * Display the farmer profile edit form.
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('farmers.profile-edit', compact('user'));
    }

    /**
     * Update the farmer profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Farmer specific fields
            'farm_name' => 'nullable|string|max:255',
            'farm_size' => 'nullable|string|max:255',
            'product_type' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0|max:100',
            'certification' => 'nullable|string|max:255',
            'farm_address' => 'nullable|string|max:255',
        ]);

        // Update user information
        $user->update($request->only([
            'first_name',
            'last_name',
            'email',
            'phone_number',
            'address'
        ]));

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            // Store new profile picture
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->update(['profile_picture' => $profilePicturePath]);
        }

        // Update farmer information
        if ($user->farmer) {
            $user->farmer->update($request->only([
                'farm_name',
                'farm_size',
                'product_type',
                'experience_years',
                'certification',
                'farm_address'
            ]));
        } else {
            // Create farmer profile if it doesn't exist
            $user->farmer()->create($request->only([
                'farm_name',
                'farm_size',
                'product_type',
                'experience_years',
                'certification',
                'farm_address'
            ]));
        }

        return redirect()->route('farmer.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Display farmer notifications.
     */
    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(10);
        
        return view('farmers.notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read.
     */
    public function markNotificationAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
        }
        
        return back();
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage; // Import the Storage facade




class ProfileController extends Controller
{
    // @desc Update profile info
    // @route PUT / profile
    public function update(Request $request): RedirectResponse
    {
        // Get logged in user
        $user = Auth::user();

        // Validate data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048'

        ]);

        // Get user name and email
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // 2. Handle avatar upload
        if ($request->hasFile('avatar')) {
            // 2a. Delete the old avatar if it exists
            if ($user->avatar) {
                // Assumes 'avatar' stores the path relative to the 'public' disk root
                // e.g., 'avatar/old_avatar.png'
                Storage::disk('public')->delete($user->avatar);
            }

            // 2b. Store the new avatar on the 'public' disk and get its path
            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            // 2c. Add/overwrite the 'avatar' key in $validatedData ONLY if a new file was uploaded
            $user->avatar = $avatarPath;
        }

        // update user info
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
    }

}

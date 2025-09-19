<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Show user profile page.
     */
    public function profile(): View
    {
        $user = Auth::user();

        return view('user.profile', compact('user'));
    }

    /**
     * Show user settings page.
     */
    public function settings(): View
    {
        $user = Auth::user();

        return view('user.settings', compact('user'));
    }

    /**
     * Update user profile.
     */
    public function updateProfile(UserProfileRequest $request): JsonResponse|RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validated();

        // Handle password update if provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully.',
                'data' => $user->fresh(),
            ]);
        }

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully.');
    }
}

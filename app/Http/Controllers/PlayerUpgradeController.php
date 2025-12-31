<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PlayerUpgradeController extends Controller
{
    /**
     * Show the upgrade form
     */
    public function show()
    {
        $user = Auth::user();

        // Check if user can upgrade
        if (! $user->canUpgradeToPlayer()) {
            return redirect()->route('profile.show')->with('error', 'You cannot upgrade to player at this time.');
        }

        return view('profile.upgrade-player', compact('user'));
    }

    /**
     * Process the upgrade request
     */
    public function upgrade(Request $request)
    {
        $user = Auth::user();

        // Check if user can upgrade
        if (! $user->canUpgradeToPlayer()) {
            return response()->json([
                'success' => false,
                'message' => __('app.auth.upgrade_failed'),
            ], 400);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'gaming_nickname' => 'required|string|max:255|unique:users,gaming_nickname,'.$user->id,
            'team_preference' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:1000',
        ], [
            'gaming_nickname.required' => __('app.auth.nickname').' is required',
            'gaming_nickname.unique' => 'This gaming nickname is already taken',
            'gaming_nickname.max' => 'Gaming nickname cannot exceed 255 characters',
            'team_preference.max' => 'Team preference cannot exceed 1000 characters',
            'description.max' => 'Description cannot exceed 1000 characters',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('app.auth.missing_requirements'),
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Upgrade user to player
            $user->upgradeToPlayer(
                $request->gaming_nickname,
                $request->team_preference,
                $request->description
            );

            return response()->json([
                'success' => true,
                'message' => __('app.auth.upgrade_success'),
                'redirect_url' => route('profile.show'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

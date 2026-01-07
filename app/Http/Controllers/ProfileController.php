<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = User::with(['teams' => function ($query) {
            $query->withPivot(['role', 'status', 'joined_at'])
                ->where('team_members.status', 'active');
        }])->find(Auth::id());

        return view('profile.show', compact('user'));
    }

    public function showUser($appId)
    {
        $user = User::with(['teams' => function ($query) {
            $query->withPivot(['role', 'status', 'joined_at'])
                ->where('team_members.status', 'active');
        }])->where('app_id', $appId)->firstOrFail();

        return view('profile.show-user', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        $games = \App\Models\Game::active()->orderBy('name')->get();

        return view('profile.edit', compact('user', 'games'));
    }

    public function update(Request $request)
    {
        $user = User::with('profile')->find(Auth::id());
        $authUser = User::find(Auth::id()); // Ensure we get User model instance

        $validationRules = [
            'full_name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bio' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date|before:today',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Add user_role validation for admins
        if ($authUser->isAdmin() && $request->has('user_role')) {
            // Super Admin cannot change their own role
            if ($authUser->isSuperAdmin() && $authUser->id === $user->id) {
                return back()->withErrors([
                    'user_role' => 'Super Admin không thể thay đổi vai trò của chính mình.',
                ])->withInput();
            }

            $validationRules['user_role'] = ['required', 'string', Rule::in(['participant', 'admin', 'super_admin'])];
        }

        $request->validate($validationRules);

        // Separate user data and profile data
        $userData = $request->only(['email']);
        $profileData = $request->only([
            'full_name',
            'bio',
            'date_of_birth',
            'phone',
            'country',
        ]);

        // Handle user_role update (only for admins and with restrictions)
        if ($authUser->isAdmin() && $request->has('user_role')) {
            // Additional security check: Super Admin cannot change their own role
            if (! ($authUser->isSuperAdmin() && $authUser->id === $user->id)) {
                // Only Super Admin can assign Super Admin role to others
                if ($request->user_role === 'super_admin' && ! $authUser->isSuperAdmin()) {
                    return back()->withErrors([
                        'user_role' => 'Chỉ Super Admin mới có thể gán quyền Super Admin cho người khác.',
                    ])->withInput();
                }

                $userData['user_role'] = $request->user_role;
            }
        }

        // Handle reset to Google avatar
        if ($request->has('reset_to_google_avatar') && $request->reset_to_google_avatar) {
            $googleAvatar = $user->profile?->google_avatar;
            if ($googleAvatar) {
                $currentAvatar = $user->profile?->avatar;
                
                // Delete old local avatar if exists
                if ($currentAvatar && !filter_var($currentAvatar, FILTER_VALIDATE_URL) && !str_starts_with($currentAvatar, 'system/')) {
                    Storage::disk('public')->delete($currentAvatar);
                }
                
                $profileData['avatar'] = $googleAvatar;
            }
        }
        // Handle system avatar selection
        elseif ($request->has('system_avatar') && $request->system_avatar) {
            $currentAvatar = $user->profile?->avatar;
            
            // Delete old local avatar if exists (but not system avatars)
            if ($currentAvatar && !filter_var($currentAvatar, FILTER_VALIDATE_URL) && !str_starts_with($currentAvatar, 'system/')) {
                Storage::disk('public')->delete($currentAvatar);
            }
            
            // Store system avatar path (e.g., "system/avatar_1.png")
            $profileData['avatar'] = $request->system_avatar;
        }
        // Handle avatar upload - save to user_profiles table
        elseif ($request->hasFile('avatar')) {
            $currentAvatar = $user->profile?->avatar;
            
            // Delete old avatar if exists and is a local file (not a URL or system avatar)
            if ($currentAvatar && !filter_var($currentAvatar, FILTER_VALIDATE_URL) && !str_starts_with($currentAvatar, 'system/')) {
                Storage::disk('public')->delete($currentAvatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $profileData['avatar'] = $avatarPath;
        }

        // Update user table (email, user_role)
        $user->update($userData);

        // Update or create user_profiles table (full_name, bio, avatar, etc.)
        if ($user->profile) {
            $user->profile->update($profileData);
        } else {
            $profileData['user_id'] = $user->id;
            \App\Models\UserProfile::create($profileData);
        }

        $message = 'Thông tin cá nhân đã được cập nhật thành công!';
        if (isset($userData['user_role']) && $userData['user_role'] !== $user->getOriginal('user_role')) {
            $message .= ' Vai trò đã được thay đổi.';
        }

        return redirect()->route('profile.show')->with('success', $message);
    }

    public function changePassword()
    {
        return view('profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::find(Auth::id());

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }

    public function searchUsers(Request $request)
    {
        $search = $request->get('search');

        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $users = \App\Models\User::where('id_app', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->orWhere('full_name', 'like', '%' . $search . '%')
            ->select('id', 'name', 'full_name', 'id_app', 'avatar')
            ->limit(10)
            ->get();

        return response()->json($users);
    }
}

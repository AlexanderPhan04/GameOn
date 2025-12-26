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

    public function showUser($id)
    {
        $user = User::with(['teams' => function ($query) {
            $query->withPivot(['role', 'status', 'joined_at'])
                ->where('team_members.status', 'active');
        }])->findOrFail($id);

        return view('profile.show-user', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id());
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

            $validationRules['user_role'] = ['required', 'string', Rule::in(['viewer', 'player', 'admin', 'super_admin'])];
        }

        $request->validate($validationRules);

        $data = $request->only([
            'full_name',
            'email',
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

                $data['user_role'] = $request->user_role;
            }
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        $message = 'Thông tin cá nhân đã được cập nhật thành công!';
        if (isset($data['user_role']) && $data['user_role'] !== $user->getOriginal('user_role')) {
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

        $users = \App\Models\User::where('id_app', 'like', '%'.$search.'%')
            ->orWhere('name', 'like', '%'.$search.'%')
            ->orWhere('full_name', 'like', '%'.$search.'%')
            ->select('id', 'name', 'full_name', 'id_app', 'avatar')
            ->limit(10)
            ->get();

        return response()->json($users);
    }
}

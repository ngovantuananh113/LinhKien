<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($request->input('phone') === '') {
            $request->merge(['phone' => null]);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30', Rule::unique('users', 'phone')->ignore($user->id)],
        ];

        $wantsPasswordChange = $request->filled('current_password')
            || $request->filled('password')
            || $request->filled('password_confirmation');

        if ($wantsPasswordChange) {
            $rules['current_password'] = ['required', 'current_password'];
            $rules['password'] = ['required', 'confirmed', Password::min(6)];
        }

        $validated = $request->validate($rules);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'] ?? null;

        if ($wantsPasswordChange && ! empty($validated['password'] ?? null)) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Đã cập nhật thông tin tài khoản.');
    }
}

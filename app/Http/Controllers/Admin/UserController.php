<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    public function index(Request $request): View|StreamedResponse
    {
        if ($request->query('export') === 'csv') {
            return $this->exportCsv($request);
        }

        $base = User::query();

        $q = $request->string('q')->trim();
        if ($q !== '') {
            $base->where(function ($query) use ($q) {
                $query->where('name', 'like', '%'.$q.'%')
                    ->orWhere('email', 'like', '%'.$q.'%');
                if (is_numeric($q)) {
                    $query->orWhere('id', (int) $q);
                }
            });
        }

        $users = (clone $base)->latest()->paginate(15)->withQueryString();

        $totalNodes = User::query()->count();
        $activeSessions = User::query()->where('updated_at', '>=', now()->subDays(7))->count();
        $systemAdmins = User::query()->where('role', 'admin')->count();
        $failedAuthPct = '0.00';

        return view('admin.users.index', compact(
            'users',
            'totalNodes',
            'activeSessions',
            'systemAdmins',
            'failedAuthPct'
        ));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['admin', 'user'])],
        ]);

        User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'role' => $data['role'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Đã thêm người dùng.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['admin', 'user'])],
        ]);

        if ($user->isAdmin()
            && $data['role'] === 'user'
            && User::query()->where('role', 'admin')->count() === 1) {
            return back()->withInput()->withErrors([
                'role' => 'Không thể bỏ vai trò quản trị: đây là tài khoản quản trị duy nhất.',
            ]);
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;
        $user->role = $data['role'];

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Đã cập nhật người dùng.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Không thể xóa tài khoản đang đăng nhập.');
        }

        if ($user->isAdmin() && User::query()->where('role', 'admin')->count() === 1) {
            return redirect()->route('admin.users.index')->with('error', 'Không thể xóa quản trị viên duy nhất của hệ thống.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Đã xóa người dùng.');
    }

    private function exportCsv(Request $request): StreamedResponse
    {
        $query = User::query()->latest();

        $q = $request->string('q')->trim();
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%'.$q.'%')
                    ->orWhere('email', 'like', '%'.$q.'%');
                if (is_numeric($q)) {
                    $sub->orWhere('id', (int) $q);
                }
            });
        }

        $filename = 'nguoi_dung_'.now()->format('Y-m-d_His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['ID', 'Tên', 'Email', 'SĐT', 'Vai trò', 'Tạo lúc', 'Cập nhật']);

            $query->chunk(100, function ($chunk) use ($handle) {
                foreach ($chunk as $user) {
                    fputcsv($handle, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->phone ?? '',
                        $user->role,
                        $user->created_at?->format('Y-m-d H:i:s'),
                        $user->updated_at?->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}

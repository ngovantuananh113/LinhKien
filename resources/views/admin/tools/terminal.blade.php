@extends('layouts.admin-synth')

@section('title', 'Dòng lệnh')

@push('head')
    <style>
        .admin-term-screen {
            font-family: ui-monospace, "Cascadia Code", "Consolas", monospace;
            background: #050508;
            border: 1px solid rgba(0, 244, 254, 0.15);
            box-shadow: inset 0 0 60px rgba(123, 47, 247, 0.06);
        }
        .admin-term-line { line-height: 1.65; }
        .admin-term-prompt { color: #00f4fe; }
        .admin-term-out { color: #958da2; }
        .admin-term-ok { color: #6ee7b7; }
    </style>
@endpush

@section('content')
    <div class="max-w-4xl">
        <header class="mb-8">
            <div class="flex items-center gap-2 mb-2">
                <span class="h-1 w-8 bg-secondary-container shrink-0"></span>
                <span class="text-secondary-container font-headline text-[10px] tracking-[0.35em] uppercase font-bold">Console</span>
            </div>
            <h1 class="font-headline text-2xl sm:text-3xl font-black text-on-background uppercase tracking-tight">Terminal hệ thống</h1>
            <p class="text-outline mt-2 text-sm font-body">Nhật ký và trạng thái tác vụ (demo — chỉ đọc).</p>
        </header>

        <div class="admin-term-screen p-4 sm:p-6 text-[12px] sm:text-[13px] overflow-x-auto">
            <div class="admin-term-line admin-term-prompt">quan_tri@hardware_os:~$ <span class="admin-term-out">sysinfo --brief</span></div>
            <div class="admin-term-line admin-term-out mt-2">kernel: Laravel {{ app()->version() }} // PHP {{ PHP_VERSION }}</div>
            <div class="admin-term-line admin-term-out">app: {{ config('app.name') }} // env: {{ config('app.env') }}</div>
            <div class="admin-term-line admin-term-ok mt-2">[OK] Kết nối cơ sở dữ liệu hoạt động.</div>
            <div class="admin-term-line admin-term-ok">[OK] Phiên quản trị đã xác thực.</div>
            <div class="admin-term-line admin-term-out mt-3">— Không có lệnh tương tác trong bản demo. Dùng menu bên trái để điều hướng.</div>
            <div class="admin-term-line admin-term-prompt mt-4">quan_tri@hardware_os:~$ <span class="animate-pulse">_</span></div>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('admin.dashboard') }}" class="text-[11px] font-headline uppercase tracking-widest text-secondary-container hover:underline">← Tổng quan</a>
            <a href="{{ route('admin.orders.index') }}" class="text-[11px] font-headline uppercase tracking-widest text-outline hover:text-primary">Đơn hàng</a>
        </div>
    </div>
@endsection

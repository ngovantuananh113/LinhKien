@extends('layouts.shop-synth')

@section('title', 'Thông tin cá nhân')

@php
    $roleLabel = $user->isAdmin() ? 'Quản trị viên' : 'Thành viên';
    $fieldClass = 'w-full bg-transparent border-0 border-b border-outline-variant focus:ring-0 focus:border-secondary-container text-on-background placeholder:text-outline-variant/60 font-headline text-sm py-1.5 transition-colors rounded-none';
@endphp

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 w-full pb-12 md:pb-16">
    <header class="mb-6 md:mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
        <div>
            <p class="font-headline text-[10px] tracking-[0.3em] uppercase text-secondary-container mb-1.5">Tài khoản</p>
            <h1 class="font-headline text-2xl md:text-3xl font-bold tracking-tight uppercase text-primary">
                Hồ sơ <span class="text-secondary-container">cá nhân</span>
            </h1>
        </div>
        <p class="text-[11px] md:text-xs text-on-surface-variant max-w-md sm:text-right leading-snug">
            Email đăng nhập không đổi tại đây.
            <a href="{{ route('orders.index') }}" class="text-secondary-container font-headline hover:underline whitespace-nowrap">Đơn hàng →</a>
        </p>
    </header>

    <form action="{{ route('profile.update') }}" method="post" class="border border-outline-variant/25 bg-surface-container-low">
        @csrf
        @method('PATCH')

        <div class="p-4 md:p-5 lg:p-6 grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 lg:divide-x lg:divide-outline-variant/20">
            {{-- Cột trái: chỉ xem + chỉnh sửa cơ bản --}}
            <div class="lg:col-span-7 space-y-5 lg:pr-2">
                <div>
                    <h2 class="font-headline text-[10px] uppercase tracking-[0.2em] text-outline mb-3">Tài khoản</h2>
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm">
                        <div class="min-w-0">
                            <span class="font-headline text-[9px] uppercase tracking-widest text-outline block mb-0.5">Email</span>
                            <span class="font-mono text-xs text-on-background break-all">{{ $user->email }}</span>
                        </div>
                        <div class="h-8 w-px bg-outline-variant/25 hidden sm:block shrink-0" aria-hidden="true"></div>
                        <div>
                            <span class="font-headline text-[9px] uppercase tracking-widest text-outline block mb-0.5">Vai trò</span>
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-headline uppercase tracking-wider border border-secondary-container/35 text-secondary-container">{{ $roleLabel }}</span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-outline-variant/15 pt-5">
                    <h2 class="font-headline text-[10px] uppercase tracking-[0.2em] text-primary mb-3">Chỉnh sửa</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5">
                        <div class="space-y-1 group">
                            <label for="name" class="text-[9px] uppercase tracking-[0.2em] font-headline text-outline group-focus-within:text-secondary-container">Họ và tên</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required autocomplete="name" class="{{ $fieldClass }}" placeholder="Nguyễn Văn A">
                            @error('name')
                                <p class="text-red-400 text-[11px] mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1 group">
                            <label for="phone" class="text-[9px] uppercase tracking-[0.2em] font-headline text-outline group-focus-within:text-secondary-container">Điện thoại</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" autocomplete="tel" class="{{ $fieldClass }}" placeholder="0900 000 000">
                            @error('phone')
                                <p class="text-red-400 text-[11px] mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cột phải: mật khẩu — gọn cho laptop --}}
            <div class="lg:col-span-5 space-y-3 lg:pl-2 pt-2 lg:pt-0 border-t border-outline-variant/15 lg:border-t-0">
                <div>
                    <h2 class="font-headline text-[10px] uppercase tracking-[0.2em] text-outline">Đổi mật khẩu</h2>
                    <p class="text-[10px] text-on-surface-variant mt-1 leading-relaxed">Để trống nếu giữ nguyên. Cần mật khẩu hiện tại khi đổi.</p>
                </div>
                <div class="space-y-3">
                    <div class="space-y-1 group">
                        <label for="current_password" class="text-[9px] uppercase tracking-[0.2em] font-headline text-outline">Hiện tại</label>
                        <input type="password" name="current_password" id="current_password" autocomplete="current-password" class="{{ $fieldClass }}" placeholder="••••••••">
                        @error('current_password')
                            <p class="text-red-400 text-[11px] mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div class="space-y-1 group">
                            <label for="password" class="text-[9px] uppercase tracking-[0.2em] font-headline text-outline">Mới</label>
                            <input type="password" name="password" id="password" autocomplete="new-password" class="{{ $fieldClass }}" placeholder="≥ 6 ký tự">
                            @error('password')
                                <p class="text-red-400 text-[11px] mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1 group">
                            <label for="password_confirmation" class="text-[9px] uppercase tracking-[0.2em] font-headline text-outline">Xác nhận</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" class="{{ $fieldClass }}" placeholder="Nhập lại">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 px-4 md:px-5 lg:px-6 py-3 md:py-3.5 border-t border-outline-variant/20 bg-surface-container-high/40">
            <div class="flex flex-wrap gap-2">
                <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-5 py-2 min-h-[40px] bg-gradient-to-r from-primary-container to-secondary-container text-on-primary font-headline text-[10px] font-bold uppercase tracking-widest hover:brightness-110 active:scale-[0.99] transition-all shadow-[0_0_16px_rgba(123,47,247,0.2)]">
                    <span class="material-symbols-outlined text-base">save</span>
                    Lưu
                </button>
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-4 py-2 min-h-[40px] border border-outline-variant/35 text-outline text-[10px] font-headline uppercase tracking-widest hover:border-primary/40 hover:text-primary transition-colors">
                    Trang chủ
                </a>
            </div>
            <span class="text-[9px] text-outline font-headline uppercase tracking-wider hidden md:inline">SYNTH_ARCHITECT</span>
        </div>
    </form>
</div>
@endsection

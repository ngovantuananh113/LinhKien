@extends('layouts.admin-synth')

@section('title', 'Thông tin cá nhân')

@php
    $roleLabel = $user->isAdmin() ? 'Quản trị viên' : 'Thành viên';
    $fieldClass = 'w-full bg-surface-container-low border border-outline-variant/35 px-4 py-3 text-on-background focus:border-secondary-container focus:ring-0 focus:outline-none font-body text-sm transition-colors';
@endphp

@section('content')
    <div class="max-w-4xl mx-auto w-full">
        <header class="mb-8">
            <div class="flex items-center gap-2 mb-2">
                <span class="h-1 w-8 bg-secondary-container shrink-0"></span>
                <span class="text-secondary-container font-headline text-[10px] tracking-[0.35em] uppercase font-bold">Tài khoản quản trị</span>
            </div>
            <h1 class="font-headline text-2xl sm:text-3xl md:text-4xl font-black text-on-background uppercase tracking-tight">Hồ sơ cá nhân</h1>
            <p class="text-outline mt-2 text-sm font-body max-w-xl">Chỉnh sửa tên, số điện thoại và mật khẩu. Email đăng nhập không đổi tại đây.</p>
        </header>

        <form action="{{ route('admin.profile.update') }}" method="post" class="border border-outline-variant/25 bg-surface-container-lowest relative overflow-hidden">
            @csrf
            @method('PATCH')

            <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-primary-container via-secondary-container to-tertiary-container opacity-80"></div>

            <div class="p-6 sm:p-8 grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
                <div class="lg:col-span-7 space-y-6">
                    <div>
                        <h2 class="font-headline text-[10px] uppercase tracking-[0.2em] text-outline mb-3">Thông tin hiển thị</h2>
                        <div class="flex flex-wrap gap-6 text-sm">
                            <div>
                                <span class="font-headline text-[9px] uppercase tracking-widest text-outline block mb-1">Email</span>
                                <span class="font-mono text-xs text-on-background break-all">{{ $user->email }}</span>
                            </div>
                            <div>
                                <span class="font-headline text-[9px] uppercase tracking-widest text-outline block mb-1">Vai trò</span>
                                <span class="inline-flex px-2 py-1 text-[10px] font-headline uppercase tracking-wider border border-secondary-container/40 text-secondary-container">{{ $roleLabel }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 pt-2 border-t border-outline-variant/15">
                        <h2 class="font-headline text-[10px] uppercase tracking-[0.2em] text-primary">Chỉnh sửa</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block font-label text-[10px] tracking-widest uppercase text-primary mb-2">Họ và tên *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required autocomplete="name" class="{{ $fieldClass }}" placeholder="Nguyễn Văn A">
                                @error('name')
                                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="phone" class="block font-label text-[10px] tracking-widest uppercase text-primary mb-2">Điện thoại</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" autocomplete="tel" class="{{ $fieldClass }}" placeholder="0900 000 000">
                                @error('phone')
                                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 space-y-4 pt-6 lg:pt-0 border-t lg:border-t-0 lg:border-l border-outline-variant/15 lg:pl-8">
                    <div>
                        <h2 class="font-headline text-[10px] uppercase tracking-[0.2em] text-outline">Đổi mật khẩu</h2>
                        <p class="text-[11px] text-on-surface-variant mt-1">Để trống nếu giữ nguyên. Cần mật khẩu hiện tại khi đổi.</p>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label for="current_password" class="block font-label text-[10px] tracking-widest uppercase text-outline mb-2">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" id="current_password" autocomplete="current-password" class="{{ $fieldClass }}">
                            @error('current_password')
                                <p class="text-error text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="password" class="block font-label text-[10px] tracking-widest uppercase text-outline mb-2">Mật khẩu mới</label>
                                <input type="password" name="password" id="password" autocomplete="new-password" class="{{ $fieldClass }}" placeholder="≥ 6 ký tự">
                                @error('password')
                                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block font-label text-[10px] tracking-widest uppercase text-outline mb-2">Xác nhận</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" class="{{ $fieldClass }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 px-6 sm:px-8 py-4 border-t border-outline-variant/20 bg-surface-container-low/50">
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[44px] bg-gradient-to-r from-primary-container to-secondary-container text-white font-headline text-[11px] font-black uppercase tracking-widest hover:brightness-110 shadow-[0_0_20px_rgba(0,244,254,0.2)] transition-all">
                        <span class="material-symbols-outlined text-lg">save</span>
                        Lưu thay đổi
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center px-5 py-3 min-h-[44px] border border-outline-variant/40 text-on-surface-variant hover:text-primary text-[11px] font-headline uppercase tracking-widest transition-colors">
                        Về tổng quan
                    </a>
                </div>
                <a href="{{ route('profile.edit') }}" class="text-[10px] text-outline hover:text-secondary-container font-headline uppercase tracking-wider">Mở hồ sơ cửa hàng (giao diện khách) →</a>
            </div>
        </form>
    </div>
@endsection

@extends('layouts.admin-synth')

@section('title', 'Thêm người dùng')

@push('head')
    <style>
        .user-form-glow {
            box-shadow: 0 0 0 1px rgba(123, 47, 247, 0.12), 0 24px 64px rgba(0, 0, 0, 0.55), 0 0 100px rgba(0, 244, 254, 0.06);
        }
        .user-form-circuit {
            height: 2px;
            width: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 244, 254, 0.5), rgba(123, 47, 247, 0.6), transparent);
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[min(78vh,calc(100vh-10rem))] py-8 sm:py-12 px-4 sm:px-6">
        <div class="w-full max-w-lg">
            <header class="text-center mb-8 sm:mb-10">
                <div class="user-form-circuit mb-6 max-w-xs mx-auto opacity-80"></div>
                <p class="text-secondary-container font-headline text-[10px] tracking-[0.4em] uppercase font-bold mb-2">Người dùng / Thêm mới</p>
                <h1 class="font-headline text-2xl sm:text-3xl md:text-4xl font-black text-on-background uppercase tracking-tight">Đăng ký node mới</h1>
                <p class="text-outline mt-3 text-sm font-body max-w-md mx-auto leading-relaxed">Tạo tài khoản trong cửa hàng — phân quyền và mật khẩu được mã hóa theo chuẩn hệ thống.</p>
            </header>

            <div class="border border-outline-variant/25 bg-[#0a0a0a] user-form-glow relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-primary-container via-secondary-container/80 to-transparent opacity-70"></div>
                <div class="p-6 sm:p-9 pl-7 sm:pl-11">
                    <form action="{{ route('admin.users.store') }}" method="post" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block font-label text-[10px] tracking-widest uppercase text-primary mb-2">Họ tên *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required autocomplete="name"
                                class="w-full bg-surface-container-low border border-outline-variant/35 px-4 py-3.5 text-on-background focus:border-secondary-container focus:ring-0 focus:outline-none font-body transition-colors">
                            @error('name')
                                <p class="text-error text-sm mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block font-label text-[10px] tracking-widest uppercase text-primary mb-2">Email *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
                                class="w-full bg-surface-container-low border border-outline-variant/35 px-4 py-3.5 text-on-background focus:border-secondary-container focus:ring-0 focus:outline-none font-body transition-colors">
                            @error('email')
                                <p class="text-error text-sm mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="block font-label text-[10px] tracking-widest uppercase text-primary mb-2">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" autocomplete="tel"
                                class="w-full bg-surface-container-low border border-outline-variant/35 px-4 py-3.5 text-on-background focus:border-secondary-container focus:ring-0 focus:outline-none font-body transition-colors">
                            @error('phone')
                                <p class="text-error text-sm mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="role" class="block font-label text-[10px] tracking-widest uppercase text-primary mb-2">Vai trò *</label>
                            <select name="role" id="role" required
                                class="w-full bg-surface-container-low border border-outline-variant/35 px-4 py-3.5 text-on-background focus:border-secondary-container focus:ring-0 focus:outline-none font-body transition-colors">
                                <option value="user" @selected(old('role', 'user') === 'user')>Khách</option>
                                <option value="admin" @selected(old('role') === 'admin')>Quản trị</option>
                            </select>
                            @error('role')
                                <p class="text-error text-sm mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="block font-label text-[10px] tracking-widest uppercase text-primary mb-2">Mật khẩu *</label>
                            <input type="password" name="password" id="password" required autocomplete="new-password"
                                class="w-full bg-surface-container-low border border-outline-variant/35 px-4 py-3.5 text-on-background focus:border-secondary-container focus:ring-0 focus:outline-none font-body transition-colors">
                            @error('password')
                                <p class="text-error text-sm mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block font-label text-[10px] tracking-widest uppercase text-primary mb-2">Xác nhận mật khẩu *</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                                class="w-full bg-surface-container-low border border-outline-variant/35 px-4 py-3.5 text-on-background focus:border-secondary-container focus:ring-0 focus:outline-none font-body transition-colors">
                        </div>
                        <div class="flex flex-col sm:flex-row flex-wrap gap-3 pt-2">
                            <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center px-10 py-3.5 min-h-[48px] bg-gradient-to-r from-primary-container to-secondary-container text-white font-label text-xs font-black uppercase tracking-[0.2em] shadow-[0_0_28px_rgba(0,244,254,0.25)] hover:brightness-110 active:scale-[0.99] transition-all">Lưu node</button>
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-8 py-3.5 min-h-[48px] border border-outline-variant/45 text-on-surface-variant hover:text-primary hover:border-primary-container/40 font-label text-xs font-bold uppercase tracking-widest transition-colors">Quay lại danh sách</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

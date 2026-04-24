@extends('layouts.auth-synth')

@section('title', 'Đăng ký tài khoản')

@section('body_class', 'auth-page-root text-on-surface font-body selection:bg-secondary-container selection:text-on-secondary-fixed min-h-screen min-h-[100dvh] flex flex-col overflow-x-hidden overflow-y-auto pb-[calc(6.5rem+env(safe-area-inset-bottom))] sm:pb-[calc(7.5rem+env(safe-area-inset-bottom))]')

@section('content')
<div class="fixed inset-0 z-0 overflow-hidden pointer-events-none max-w-[100vw]">
    <div class="absolute top-[-15%] right-[-20%] sm:top-[-10%] sm:right-[-5%] w-[min(100vw,28rem)] h-[min(100vw,28rem)] sm:w-[600px] sm:h-[600px] bg-primary-container/10 blur-[80px] sm:blur-[120px] rounded-full"></div>
    <div class="absolute bottom-[-15%] left-[-20%] sm:bottom-[-10%] sm:left-[-5%] w-[min(90vw,24rem)] h-[min(90vw,24rem)] sm:w-[500px] sm:h-[500px] bg-secondary-container/10 blur-[70px] sm:blur-[100px] rounded-full"></div>
</div>

<div class="relative z-10 flex flex-1 flex-col justify-center w-full px-3 sm:px-6 py-6 sm:py-8">
<main class="auth-stage auth-form-shell w-full">
    <div class="text-center mb-5 sm:mb-6">
        <h1 class="font-headline text-xl sm:text-2xl md:text-3xl font-bold tracking-tighter text-primary drop-shadow-[0_0_12px_rgba(210,187,255,0.4)] break-words">
            SYNTH_ARCHITECT
        </h1>
        <p class="font-headline text-[9px] sm:text-[10px] tracking-[0.12em] sm:tracking-[0.18em] uppercase text-secondary-fixed mt-1.5 opacity-90 max-w-md mx-auto leading-relaxed px-1">
            Đăng ký tài khoản mới
        </p>
    </div>

    <div class="glass-card neon-glow-purple w-full p-4 sm:p-5 border border-outline-variant/20 relative group transition-all duration-500">
        <div class="absolute inset-0 border border-secondary-container/0 group-hover:border-secondary-container/20 transition-colors pointer-events-none"></div>

        <header class="mb-4 sm:mb-5 flex flex-row justify-between items-center gap-3">
            <div class="min-w-0 flex-1">
                <h2 class="font-headline text-sm sm:text-base font-bold text-on-surface uppercase tracking-tight">Thông tin đăng ký</h2>
                <div class="h-px w-10 bg-secondary-container mt-1.5"></div>
            </div>
            <div class="flex items-center gap-1.5 shrink-0">
                <span class="w-1.5 h-1.5 rounded-full bg-secondary-container animate-pulse"></span>
                <span class="font-headline text-[9px] tracking-widest text-secondary-fixed">Đang kết nối</span>
            </div>
        </header>

        @if ($errors->any())
            <ul class="text-red-400 text-xs mb-3 space-y-0.5 list-none p-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        @endif

        <form class="space-y-4" action="{{ route('register') }}" method="post">
            @csrf
            <div class="space-y-1.5">
                <label class="font-headline text-[9px] sm:text-[10px] tracking-widest uppercase text-outline block" for="reg_name">Họ và tên</label>
                <div class="relative">
                    <input
                        class="w-full bg-transparent border-0 border-b-2 border-outline-variant py-2 pr-9 pl-0 focus:ring-0 focus:border-secondary-container text-secondary-fixed placeholder:text-outline/40 font-body text-sm transition-all"
                        id="reg_name"
                        name="name"
                        type="text"
                        placeholder="Nguyễn Văn A"
                        autocomplete="name"
                        value="{{ old('name') }}"
                        required
                    >
                    <span class="absolute right-0 top-1/2 -translate-y-1/2 text-outline/50 material-symbols-outlined pointer-events-none text-lg">person</span>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="font-headline text-[9px] sm:text-[10px] tracking-widest uppercase text-outline block" for="reg_email">Email</label>
                <div class="relative">
                    <input
                        class="w-full bg-transparent border-0 border-b-2 border-outline-variant py-2 pr-9 pl-0 focus:ring-0 focus:border-secondary-container text-secondary-fixed placeholder:text-outline/40 font-body text-sm transition-all"
                        id="reg_email"
                        name="email"
                        type="email"
                        inputmode="email"
                        placeholder="ten@email.com"
                        autocomplete="email"
                        value="{{ old('email') }}"
                        required
                    >
                    <span class="absolute right-0 top-1/2 -translate-y-1/2 text-outline/50 material-symbols-outlined pointer-events-none text-lg">alternate_email</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5 min-w-0">
                    <label class="font-headline text-[9px] sm:text-[10px] tracking-widest uppercase text-outline block" for="reg_phone">Số điện thoại</label>
                    <div class="relative">
                        <input
                            class="w-full bg-transparent border-0 border-b-2 border-outline-variant py-2 pr-9 pl-0 focus:ring-0 focus:border-secondary-container text-secondary-fixed placeholder:text-outline/40 font-body text-sm transition-all"
                            id="reg_phone"
                            name="phone"
                            type="tel"
                            inputmode="tel"
                            placeholder="0900 000 000"
                            autocomplete="tel"
                            value="{{ old('phone') }}"
                        >
                        <span class="absolute right-0 top-1/2 -translate-y-1/2 text-outline/50 material-symbols-outlined pointer-events-none text-lg">phone_iphone</span>
                    </div>
                </div>
                <div class="space-y-1.5 min-w-0">
                    <label class="font-headline text-[9px] sm:text-[10px] tracking-widest uppercase text-outline block" for="reg_password">Mật khẩu</label>
                    <div class="relative">
                        <input
                            class="w-full bg-transparent border-0 border-b-2 border-outline-variant py-2 pr-9 pl-0 focus:ring-0 focus:border-secondary-container text-secondary-fixed placeholder:text-outline/40 font-body text-sm transition-all"
                            id="reg_password"
                            name="password"
                            type="password"
                            placeholder="••••••••"
                            autocomplete="new-password"
                            required
                        >
                        <span class="absolute right-0 top-1/2 -translate-y-1/2 text-outline/50 material-symbols-outlined pointer-events-none text-lg">encrypted</span>
                    </div>
                </div>
            </div>

            <div class="space-y-1.5 min-w-0">
                <label class="font-headline text-[9px] sm:text-[10px] tracking-widest uppercase text-outline block" for="reg_password_confirmation">Xác nhận mật khẩu</label>
                <div class="relative">
                    <input
                        class="w-full bg-transparent border-0 border-b-2 border-outline-variant py-2 pr-9 pl-0 focus:ring-0 focus:border-secondary-container text-secondary-fixed placeholder:text-outline/40 font-body text-sm transition-all"
                        id="reg_password_confirmation"
                        name="password_confirmation"
                        type="password"
                        placeholder="••••••••"
                        autocomplete="new-password"
                        required
                    >
                    <span class="absolute right-0 top-1/2 -translate-y-1/2 text-outline/50 material-symbols-outlined pointer-events-none text-lg">encrypted</span>
                </div>
            </div>

            <div class="flex items-start gap-2.5 pt-0.5">
                <input class="mt-0.5 h-4 w-4 shrink-0 rounded-none border-outline-variant bg-surface-container text-primary-container focus:ring-offset-surface focus:ring-primary-container accent-primary-container cursor-pointer" id="terms" name="terms" type="checkbox" value="1">
                <label class="text-[10px] sm:text-[11px] leading-snug text-outline break-words" for="terms">
                    Tôi đồng ý với <span class="text-secondary-fixed">điều khoản sử dụng</span> và <span class="text-secondary-fixed">chính sách bảo mật</span> của cửa hàng.
                </label>
            </div>

            <button class="w-full group relative overflow-hidden bg-gradient-to-r from-primary-container to-secondary-container p-px transition-all duration-300 hover:shadow-[0_0_20px_rgba(0,244,254,0.4)] active:scale-[0.98] mt-1" type="submit">
                <div class="bg-[#131313] py-2.5 sm:py-3 flex items-center justify-center gap-2 transition-colors group-hover:bg-transparent min-h-[2.75rem] sm:min-h-0">
                    <span class="font-headline font-bold text-xs uppercase tracking-widest text-secondary-fixed group-hover:text-[#131313] text-center px-2">Đăng ký</span>
                    <span class="material-symbols-outlined text-secondary-fixed group-hover:text-[#131313] text-base shrink-0" style="font-variation-settings: 'FILL' 1;">bolt</span>
                </div>
            </button>
        </form>

        <div class="mt-5 pt-4 border-t border-outline-variant/20 flex justify-center">
            <a class="font-headline text-[10px] tracking-widest text-outline hover:text-primary transition-colors uppercase group inline-flex items-center gap-1.5" href="{{ route('login') }}">
                <span class="material-symbols-outlined text-sm group-hover:-translate-x-0.5 transition-transform shrink-0">arrow_back</span>
                <span>Đã có tài khoản? Đăng nhập</span>
            </a>
        </div>
    </div>

    <p class="mt-5 text-center text-[9px] font-headline tracking-widest text-outline-variant/80 uppercase opacity-60">
        Phiên bản giao diện 2.04
    </p>
</main>
</div>

<div class="fixed bottom-16 sm:bottom-20 right-2 sm:right-6 lg:right-12 opacity-15 sm:opacity-20 pointer-events-none hidden lg:block z-[1] max-w-[40vw]">
    <img alt="" class="w-40 lg:w-56 max-h-[30vh] object-contain grayscale contrast-150 mix-blend-screen" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDSxR48UHUTRxvpnl5BliKAiwjVJQywDsYjCfWJ-vuInSSW2lu1vnbWBK229LIi-IP8tMZpJfG0d7kk5JSX05B7dRu3Y7GSf5aA3tmj-qkXC1mCZoYABqZrh7k_7YjKfFNol3VZd-OlA4LeC_FdkWGeDcca4rBHRqqAZblhR7KnTc02oOxJbXwv29X2JJOsnvSCdaxGveUdtkIuZZxJkEL4UqRwvFcXPmx5PKriTXKZJitRJ9dt3hlXkKSGGbPUq9ewEhT90o460v4">
</div>

@include('components.auth-synth-footer')
@endsection

@extends('layouts.auth-synth')

@section('title', 'Đăng nhập')

@section('body_class', 'auth-page-root font-body text-on-surface selection:bg-primary-container selection:text-white min-h-screen min-h-[100dvh] flex flex-col overflow-x-hidden overflow-y-auto pb-[calc(6.5rem+env(safe-area-inset-bottom))] sm:pb-[calc(7.5rem+env(safe-area-inset-bottom))]')

@section('content')
<div class="absolute inset-0 pointer-events-none overflow-hidden max-w-[100vw]">
    <div class="circuit-line top-[20%] left-0"></div>
    <div class="circuit-line top-[80%] left-0"></div>
    <div class="circuit-line-v left-[15%] top-0 hidden sm:block"></div>
    <div class="circuit-line-v left-[85%] top-0 hidden sm:block"></div>
    <div class="absolute top-1/4 right-2 sm:right-10 w-48 sm:w-64 h-48 sm:h-64 bg-primary-container/10 blur-[80px] sm:blur-[100px]"></div>
    <div class="absolute bottom-1/4 left-2 sm:left-10 w-48 sm:w-64 h-48 sm:h-64 bg-secondary-container/10 blur-[80px] sm:blur-[100px]"></div>
</div>

<div class="relative z-10 flex flex-1 flex-col justify-center w-full px-3 sm:px-6 py-6 sm:py-8">
<main class="auth-stage auth-form-shell w-full">
    <div class="text-center mb-5 sm:mb-6">
        <h1 class="font-headline text-xl sm:text-2xl md:text-3xl font-bold tracking-tighter text-primary drop-shadow-[0_0_8px_rgba(210,187,255,0.5)] break-words px-1">
            SYNTH_ARCHITECT
        </h1>
        <p class="font-headline text-[9px] sm:text-[10px] tracking-[0.15em] sm:tracking-[0.2em] uppercase text-secondary-fixed mt-1.5 px-2 leading-relaxed">
            Cổng truy cập hệ thống
        </p>
    </div>

    <div class="glass-card neon-glow-purple w-full p-4 sm:p-5 border border-outline-variant/20 relative group transition-all duration-500">
        <div class="absolute inset-0 border border-secondary-container/0 group-hover:border-secondary-container/20 transition-colors pointer-events-none"></div>
        <header class="mb-4 sm:mb-5">
            <h2 class="font-headline text-sm sm:text-base font-bold text-on-surface uppercase tracking-wide flex flex-wrap items-center gap-2">
                <span class="material-symbols-outlined text-primary text-lg shrink-0">lock_open</span>
                <span class="min-w-0">Đăng nhập</span>
            </h2>
            <div class="h-px w-full bg-gradient-to-r from-primary-container to-transparent mt-2 sm:mt-3"></div>
        </header>

        @if ($errors->any())
            <p class="text-red-400 text-xs sm:text-sm mb-3 text-center">{{ $errors->first() }}</p>
        @endif

        <form class="space-y-4" action="{{ route('login') }}" method="post">
            @csrf
            <div class="space-y-1.5">
                <label class="font-headline text-[9px] sm:text-[10px] tracking-widest uppercase text-outline block" for="architect_id">Email</label>
                <div class="relative">
                    <input
                        class="w-full bg-transparent border-0 border-b-2 border-outline-variant py-2 pr-9 pl-0 focus:ring-0 focus:border-secondary-container text-secondary-fixed placeholder:text-outline/40 font-body text-sm transition-all @error('email') border-red-500/80 @enderror"
                        id="architect_id"
                        name="email"
                        type="email"
                        inputmode="email"
                        placeholder="ten@email.com"
                        autocomplete="username"
                        value="{{ old('email') }}"
                        required
                    >
                    <span class="absolute right-0 top-1/2 -translate-y-1/2 text-outline/50 material-symbols-outlined pointer-events-none text-lg">alternate_email</span>
                </div>
            </div>
            <div class="space-y-1.5">
                <label class="font-headline text-[9px] sm:text-[10px] tracking-widest uppercase text-outline block" for="secure_protocol">Mật khẩu</label>
                <div class="relative">
                    <input
                        class="w-full bg-transparent border-0 border-b-2 border-outline-variant py-2 pr-9 pl-0 focus:ring-0 focus:border-secondary-container text-secondary-fixed placeholder:text-outline/40 font-body text-sm transition-all"
                        id="secure_protocol"
                        name="password"
                        type="password"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        required
                    >
                    <span class="absolute right-0 top-1/2 -translate-y-1/2 text-outline/50 material-symbols-outlined pointer-events-none text-lg">key</span>
                </div>
            </div>
            <button class="w-full group relative overflow-hidden bg-gradient-to-r from-primary-container to-secondary-container p-px transition-all duration-300 hover:shadow-[0_0_20px_rgba(0,244,254,0.4)] active:scale-[0.98] mt-1" type="submit">
                <div class="bg-[#131313] py-2.5 sm:py-3 flex items-center justify-center gap-2 transition-colors group-hover:bg-transparent min-h-[2.75rem] sm:min-h-0">
                    <span class="font-headline font-bold text-xs uppercase tracking-widest text-secondary-fixed group-hover:text-[#131313] text-center px-2">Đăng nhập</span>
                    <span class="material-symbols-outlined text-secondary-fixed group-hover:text-[#131313] text-base shrink-0" style="font-variation-settings: 'FILL' 1;">bolt</span>
                </div>
            </button>
        </form>

        <footer class="mt-5 flex flex-col gap-2 sm:gap-3 text-center">
            <a class="font-headline text-[9px] sm:text-[10px] tracking-widest text-outline hover:text-primary transition-colors flex items-center justify-center gap-1.5 py-1" href="#">
                <span class="material-symbols-outlined text-sm">settings_backup_restore</span>
                Khôi phục mật khẩu
            </a>
            <div class="flex items-center gap-2 sm:gap-3 px-2">
                <div class="h-px flex-1 bg-outline-variant/30"></div>
                <span class="font-headline text-[9px] text-outline/50 shrink-0">HOẶC</span>
                <div class="h-px flex-1 bg-outline-variant/30"></div>
            </div>
            <a class="group flex items-center justify-center gap-2 font-headline text-xs font-bold text-secondary-fixed hover:text-primary transition-colors uppercase tracking-tight py-1" href="{{ route('register') }}">
                <span class="text-center">Tạo tài khoản mới</span>
                <span class="material-symbols-outlined text-sm transition-transform group-hover:translate-x-1 shrink-0">arrow_forward</span>
            </a>
        </footer>
    </div>

    <div class="mt-6 sm:mt-8 grid grid-cols-1 gap-2 sm:grid-cols-2 sm:gap-3 opacity-25 px-1 text-[7px] sm:text-[8px]">
        <div class="flex flex-col gap-0.5 text-left min-w-0">
            <span class="font-headline tracking-tighter uppercase text-outline break-words">Phiên bản giao diện 4.0</span>
        </div>
        <div class="flex flex-col gap-0.5 text-left sm:text-right min-w-0">
            <span class="font-headline tracking-tighter uppercase text-outline break-words leading-snug">Bảo mật: TLS 1.3</span>
        </div>
    </div>
</main>
</div>

<div class="absolute left-0 top-[18%] xl:left-[-5%] xl:top-[20%] hidden xl:block pointer-events-none max-w-[40vw]">
    <div class="w-72 xl:w-96 max-h-[70vh] xl:h-[600px] bg-surface-container-low border border-outline-variant/10 p-4 xl:p-6 flex flex-col justify-between opacity-40">
        <div class="w-full aspect-square bg-surface-container-highest flex items-center justify-center overflow-hidden">
            <img alt="" class="w-full h-full object-cover opacity-60 mix-blend-screen" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA5oj2gQVfHuC_osBaNpdsvOXmoPU1kmTn_RBpjv09usyaFzOaEkkx4wUM6mm_YB72dMnVZun8uq7_efUeovDtjrsuYYcDh95iHCU4kEdnTFKPm0HYfvD8JHpCMv8whruQJmmdoKZnqVljIBk_amqggoBuaTbCgi7jECxdrPso_TSmXrMVb5NCF7mZRrhBz-Oag_BrfaPHjIA7v51kyA8LPeAF9F6mqeoes5YrKmlYWkFpuuqQwuo7mZS8BJTezrwEsfGsYVsJDhzg">
        </div>
        <div class="space-y-2 xl:space-y-3 mt-3">
            <div class="h-2 w-full bg-primary-container/20"></div>
            <div class="h-2 w-3/4 bg-primary-container/20"></div>
            <div class="h-2 w-1/2 bg-primary-container/20"></div>
        </div>
    </div>
</div>

<div class="absolute right-0 bottom-[8%] xl:right-[-5%] xl:bottom-[10%] hidden xl:block pointer-events-none max-w-[40vw]">
    <div class="w-72 xl:w-80 max-h-[55vh] xl:h-[400px] bg-surface-container-low border border-outline-variant/10 p-4 xl:p-6 flex flex-col justify-between opacity-40" style="clip-path: polygon(10% 0, 100% 0, 100% 100%, 0 100%, 0 10%);">
        <div class="space-y-2 xl:space-y-3 mb-3 xl:mb-6">
            <div class="flex justify-between font-headline text-[10px] text-primary gap-2">
                <span class="truncate">Nhiệt độ lõi</span>
                <span class="shrink-0">32°C</span>
            </div>
            <div class="h-1 w-full bg-surface-container-highest">
                <div class="h-full bg-secondary-container w-[40%] shadow-[0_0_10px_#00f4fe]"></div>
            </div>
        </div>
        <img alt="" class="w-full aspect-square object-cover opacity-60 max-h-[40vh]" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBR4kW0kqozEkX2IFnsrmmzevw9XVYj9Z93q8K6hKaa5p6PPfvlqVNm87fNg6I1wlqXoYH7ytm5jgCDGEar6aDfJCXdiiAVkaZ5X-YMjoNR-SqAkooBk3zI6TRHqtPHppkDeCDV1N3a1voZUffzYsb0vjB_fw19hNxmLyZzuaKP6Oc4j9M8HyrMdKJER3q0m3JxFIKZb_VOJAUAFwK8hgxb63o0QDYKun5OvXtvUilq6BGh-OMOMuWiz6_5IlqIiK4oTGXH3RDwQxw">
    </div>
</div>

@include('components.auth-synth-footer')
@endsection

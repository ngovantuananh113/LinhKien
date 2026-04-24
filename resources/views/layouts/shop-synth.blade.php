<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'Cửa hàng') — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        background: "#131313",
                        "on-surface": "#e5e2e1",
                        "on-surface-variant": "#ccc3d9",
                        "on-background": "#e5e2e1",
                        "on-primary": "#3e008e",
                        "on-primary-fixed": "#25005a",
                        "on-primary-fixed-variant": "#5900c6",
                        "on-secondary-container": "#006c71",
                        primary: "#d2bbff",
                        "primary-container": "#7b2ff7",
                        "primary-fixed": "#eaddff",
                        "secondary-fixed": "#63f7ff",
                        "secondary-container": "#00f4fe",
                        "secondary-fixed": "#63f7ff",
                        "tertiary-container": "#b800a5",
                        outline: "#958da2",
                        "surface-container": "#201f1f",
                        "surface-container-low": "#1c1b1b",
                        "surface-container-lowest": "#0e0e0e",
                        "surface-container-high": "#2a2a2a",
                        "surface-container-highest": "#353534",
                        "outline-variant": "#4a4456",
                    },
                    fontFamily: {
                        headline: ["Space Grotesk", "sans-serif"],
                        body: ["Manrope", "sans-serif"],
                        label: ["Space Grotesk", "sans-serif"],
                    },
                    borderRadius: { DEFAULT: "0px", lg: "0px", xl: "0px", full: "9999px" },
                },
            },
        };
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .chamfer-tl-br {
            clip-path: polygon(20px 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%, 0 20px);
        }
        html { scrollbar-gutter: stable; }
        body.synth-scroll-dark {
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 244, 254, 0.35) rgba(255, 255, 255, 0.05);
        }
        body.synth-scroll-dark::-webkit-scrollbar {
            width: 9px;
            height: 9px;
        }
        body.synth-scroll-dark::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.4);
        }
        body.synth-scroll-dark::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(123, 47, 247, 0.45), rgba(0, 244, 254, 0.4));
            border-radius: 999px;
            border: 1px solid rgba(0, 0, 0, 0.35);
        }
        body.synth-scroll-dark::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(123, 47, 247, 0.65), rgba(0, 244, 254, 0.55));
        }
        .synth-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 244, 254, 0.45) rgba(255, 255, 255, 0.06);
        }
        .synth-scrollbar::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }
        .synth-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.35);
            border-radius: 999px;
            margin: 2px 0;
        }
        .synth-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(123, 47, 247, 0.55), rgba(0, 244, 254, 0.5));
            border-radius: 999px;
            border: 1px solid rgba(0, 0, 0, 0.45);
            box-shadow: 0 0 8px rgba(0, 244, 254, 0.15);
        }
        .synth-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(123, 47, 247, 0.75), rgba(0, 244, 254, 0.7));
        }
        .synth-scrollbar::-webkit-scrollbar-corner {
            background: transparent;
        }
    </style>
    @stack('head')
</head>
<body class="synth-scroll-dark bg-background text-on-surface font-body min-h-screen flex flex-col selection:bg-secondary-container selection:text-on-secondary-container">
    {{-- TopNavBar — đồng bộ homepage_build_your_ultimate_pc --}}
    <header class="fixed top-0 left-0 w-full z-50 flex flex-wrap justify-between items-center gap-y-2 px-4 sm:px-8 py-4 bg-[#131313]/80 backdrop-blur-xl shadow-[0_4px_20px_rgba(0,0,0,0.5)]">
        <a href="{{ route('home') }}" class="text-xl sm:text-2xl font-bold tracking-widest text-primary drop-shadow-[0_0_8px_rgba(210,187,255,0.8)] font-headline tracking-tighter uppercase shrink-0">
            SYNTH_ARCHITECT
        </a>

        <nav class="hidden md:flex items-center gap-6 lg:gap-8 font-headline tracking-tighter uppercase text-sm" aria-label="Menu chính">
            <a href="{{ route('home') }}" class="pb-1 transition-all duration-150 {{ request()->routeIs('home') ? 'text-secondary-container border-b-2 border-secondary-container hover:drop-shadow-[0_0_10px_rgba(0,244,254,0.6)]' : 'text-primary/70 hover:text-primary hover:drop-shadow-[0_0_10px_rgba(0,244,254,0.6)]' }}">Trang chủ</a>
            <a href="{{ route('products.index') }}" class="pb-1 transition-all duration-150 {{ request()->routeIs('products.*') ? 'text-secondary-container border-b-2 border-secondary-container hover:drop-shadow-[0_0_10px_rgba(0,244,254,0.6)]' : 'text-primary/70 hover:text-primary hover:drop-shadow-[0_0_10px_rgba(0,244,254,0.6)]' }}">Sản phẩm</a>
            @auth
                <a href="{{ route('cart.index') }}" class="pb-1 transition-all duration-150 {{ request()->routeIs('cart.*') ? 'text-secondary-container border-b-2 border-secondary-container' : 'text-primary/70 hover:text-primary hover:drop-shadow-[0_0_10px_rgba(0,244,254,0.6)]' }}">Giỏ hàng</a>
                <a href="{{ route('orders.index') }}" class="pb-1 transition-all duration-150 {{ request()->routeIs('orders.index', 'orders.show') ? 'text-secondary-container border-b-2 border-secondary-container' : 'text-primary/70 hover:text-primary hover:drop-shadow-[0_0_10px_rgba(0,244,254,0.6)]' }}">Đơn hàng</a>
            @else
                <a href="{{ route('login') }}" class="pb-1 text-primary/70 hover:text-primary hover:drop-shadow-[0_0_10px_rgba(0,244,254,0.6)] transition-all duration-150">Giỏ hàng</a>
                <a href="{{ route('login') }}" class="pb-1 text-primary/70 hover:text-primary hover:drop-shadow-[0_0_10px_rgba(0,244,254,0.6)] transition-all duration-150">Đơn hàng</a>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="pb-1 transition-all duration-150 {{ request()->routeIs('login') ? 'text-secondary-container border-b-2 border-secondary-container' : 'text-primary/70 hover:text-primary' }}">Đăng nhập</a>
                <a href="{{ route('register') }}" class="pb-1 transition-all duration-150 {{ request()->routeIs('register') ? 'text-secondary-container border-b-2 border-secondary-container' : 'text-primary/70 hover:text-primary' }}">Đăng ký</a>
            @else
                <form action="{{ route('logout') }}" method="post" class="inline">
                    @csrf
                    <button type="submit" class="pb-1 text-primary/70 hover:text-secondary-container uppercase font-headline tracking-tighter text-sm">Đăng xuất</button>
                </form>
            @endguest
            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="text-primary hover:text-secondary-container pb-1">Quản trị</a>
            @endif
        </nav>

        <div class="flex items-center gap-2 sm:gap-4 text-primary ml-auto md:ml-0">
            @auth
                <a href="{{ route('cart.index') }}" class="material-symbols-outlined hover:drop-shadow-[0_0_10px_rgba(0,244,254,0.6)] transition-all active:scale-95 p-1" title="Giỏ hàng">shopping_cart</a>
                <a href="{{ route('profile.edit') }}" class="material-symbols-outlined hover:drop-shadow-[0_0_10px_rgba(0,244,254,0.6)] transition-all active:scale-95 p-1 {{ request()->routeIs('profile.edit') ? 'text-secondary-container drop-shadow-[0_0_8px_rgba(0,244,254,0.5)]' : '' }}" title="Thông tin cá nhân">account_circle</a>
            @else
                <a href="{{ route('login') }}" class="material-symbols-outlined hover:drop-shadow-[0_0_10px_rgba(0,244,254,0.6)] transition-all active:scale-95 p-1" title="Giỏ hàng">shopping_cart</a>
                {{-- Chưa đăng nhập: icon đăng nhập (mũi tên vào cửa); đã đăng nhập: account_circle --}}
                <a href="{{ route('login') }}" class="material-symbols-outlined text-secondary-container hover:drop-shadow-[0_0_10px_rgba(0,244,254,0.6)] transition-all active:scale-95 p-1" title="Đăng nhập">login</a>
            @endauth
            <button type="button" id="shop-mobile-open" class="md:hidden material-symbols-outlined p-1" aria-expanded="false" aria-controls="shop-mobile-panel">menu</button>
        </div>
        <div class="bg-gradient-to-r from-primary-container to-secondary-container h-0.5 w-full absolute bottom-0 left-0 pointer-events-none"></div>
    </header>

    {{-- Menu mobile --}}
    <div id="shop-mobile-overlay" class="fixed inset-0 bg-black/70 z-40 hidden md:hidden" aria-hidden="true"></div>
    <div id="shop-mobile-panel" class="fixed top-0 right-0 z-50 h-full w-[min(100%,20rem)] bg-[#0e0e0e] border-l border-outline-variant/30 transform translate-x-full transition-transform duration-200 ease-out md:hidden flex flex-col pt-20 px-6 pb-8 shadow-2xl">
        <button type="button" id="shop-mobile-close" class="absolute top-4 right-4 material-symbols-outlined text-primary">close</button>
        <nav class="flex flex-col gap-4 font-headline uppercase text-sm tracking-tight">
            <a href="{{ route('home') }}" class="text-on-surface hover:text-secondary-container py-2 border-b border-outline-variant/20">Trang chủ</a>
            <a href="{{ route('products.index') }}" class="text-on-surface hover:text-secondary-container py-2 border-b border-outline-variant/20">Sản phẩm</a>
            @auth
                <a href="{{ route('cart.index') }}" class="text-on-surface hover:text-secondary-container py-2 border-b border-outline-variant/20">Giỏ hàng</a>
                <a href="{{ route('profile.edit') }}" class="text-on-surface hover:text-secondary-container py-2 border-b border-outline-variant/20 {{ request()->routeIs('profile.edit') ? 'text-secondary-container' : '' }}">Thông tin cá nhân</a>
                <a href="{{ route('orders.index') }}" class="text-on-surface hover:text-secondary-container py-2 border-b border-outline-variant/20">Đơn hàng</a>
            @else
                <a href="{{ route('login') }}" class="text-on-surface hover:text-secondary-container py-2 border-b border-outline-variant/20">Giỏ hàng</a>
                <a href="{{ route('login') }}" class="text-on-surface hover:text-secondary-container py-2 border-b border-outline-variant/20">Đơn hàng</a>
                <a href="{{ route('register') }}" class="text-on-surface hover:text-secondary-container py-2 border-b border-outline-variant/20">Đăng ký</a>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="text-secondary-container py-2 border-b border-outline-variant/20">Đăng nhập</a>
            @else
                <form action="{{ route('logout') }}" method="post" class="pt-2">
                    @csrf
                    <button type="submit" class="text-left w-full text-on-surface hover:text-primary uppercase">Đăng xuất</button>
                </form>
            @endguest
            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="text-primary py-2">Quản trị</a>
            @endif
        </nav>
    </div>

    <main class="flex-1 w-full pt-24">
        @if(session('success') || session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-8 w-full pt-4">
                @if(session('success'))
                    <div class="mb-4 px-4 py-3 border border-secondary-container/40 text-secondary-container text-sm font-body">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-4 px-4 py-3 border border-red-400/50 text-red-300 text-sm">{{ session('error') }}</div>
                @endif
            </div>
        @endif
        @yield('content')
    </main>

    {{-- Footer chung — theo thiết kế --}}
    <footer class="w-full py-10 sm:py-12 px-6 sm:px-8 flex flex-col md:flex-row justify-between items-center gap-6 bg-[#0e0e0e] border-t border-primary-container/20 mt-auto">
        <div class="font-body text-xs tracking-widest uppercase text-primary/70 text-center md:text-left">
            © {{ date('Y') }} SYNTH_ARCHITECT. Hệ thống hoạt động bình thường.
        </div>
        <div class="flex flex-wrap justify-center gap-6 sm:gap-8">
            <span class="font-body text-xs tracking-widest uppercase text-primary/40 cursor-default">Hỗ trợ</span>
            <span class="font-body text-xs tracking-widest uppercase text-primary/40 cursor-default">Liên hệ</span>
            <span class="font-body text-xs tracking-widest uppercase text-primary/40 cursor-default">Bảo mật</span>
            <span class="font-body text-xs tracking-widest uppercase text-primary/40 cursor-default">Điều khoản</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="w-2 h-2 rounded-full bg-secondary-container animate-pulse" aria-hidden="true"></span>
            <span class="font-body text-[10px] tracking-widest text-secondary-container">Hệ thống trực tuyến</span>
        </div>
    </footer>

    <script>
        (function () {
            var openBtn = document.getElementById('shop-mobile-open');
            var closeBtn = document.getElementById('shop-mobile-close');
            var panel = document.getElementById('shop-mobile-panel');
            var overlay = document.getElementById('shop-mobile-overlay');
            function openM() {
                panel.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                openBtn.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
            }
            function closeM() {
                panel.classList.add('translate-x-full');
                overlay.classList.add('hidden');
                openBtn.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            }
            openBtn && openBtn.addEventListener('click', openM);
            closeBtn && closeBtn.addEventListener('click', closeM);
            overlay && overlay.addEventListener('click', closeM);
            panel && panel.querySelectorAll('a').forEach(function (a) {
                a.addEventListener('click', function () { closeM(); });
            });
        })();
    </script>
    @include('partials.synth-confirm-modal')
    @stack('scripts')
</body>
</html>

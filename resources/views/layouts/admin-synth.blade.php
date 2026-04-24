<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị') — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "background": "#131313",
                        "surface": "#131313",
                        "surface-container": "#201f1f",
                        "surface-container-low": "#1c1b1b",
                        "surface-container-lowest": "#0e0e0e",
                        "surface-container-high": "#2a2a2a",
                        "surface-container-highest": "#353534",
                        "primary": "#d2bbff",
                        "primary-container": "#7b2ff7",
                        "secondary": "#e6feff",
                        "secondary-container": "#00f4fe",
                        "on-background": "#e5e2e1",
                        "on-surface": "#e5e2e1",
                        "on-surface-variant": "#ccc3d9",
                        "outline-variant": "#4a4456",
                        "outline": "#958da2",
                        "error": "#ffb4ab",
                        "tertiary": "#fface8",
                        "tertiary-container": "#b800a5",
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
        .chamfer-tr-bl { clip-path: polygon(0% 0%, 95% 0%, 100% 15%, 100% 100%, 5% 100%, 0% 85%); }
        .admin-core-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .admin-core-scroll::-webkit-scrollbar-track { background: #0e0e0e; }
        .admin-core-scroll::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #7b2ff7, #00f4fe); border-radius: 999px; }
    </style>
    @stack('head')
</head>
<body class="bg-[#0b0b0c] text-on-background font-body min-h-screen selection:bg-primary-container selection:text-white admin-core-scroll">

    <div id="admin-sidebar-overlay" class="fixed inset-0 bg-black/65 z-[45] hidden lg:hidden" aria-hidden="true"></div>

    {{-- SideNavBar — CORE_ADMIN (thiết kế admin_system_overview) --}}
    <aside id="admin-sidebar" class="fixed left-0 top-0 z-50 h-full w-64 bg-[#0e0e0e] flex flex-col border-r border-outline-variant/15 -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-out">
        <div class="p-5 flex flex-col gap-1 shrink-0 border-b border-[#1c1c1c]">
            <span class="text-lg font-black text-primary font-headline tracking-tighter">Bảng điều khiển</span>
            <span class="text-[10px] font-bold text-gray-500 font-headline tracking-[0.3em]">PHIÊN BẢN ỔN ĐỊNH</span>
        </div>
        <nav class="flex-1 mt-4 px-3 space-y-1 overflow-y-auto admin-core-scroll">
            @php
                $nav = [
                    ['route' => 'admin.dashboard', 'icon' => 'dashboard', 'label' => 'Tổng quan', 'match' => ['admin.dashboard']],
                    ['route' => 'admin.categories.index', 'icon' => 'account_tree', 'label' => 'Danh mục', 'match' => ['admin.categories.*']],
                    ['route' => 'admin.products.index', 'icon' => 'memory', 'label' => 'Sản phẩm', 'match' => ['admin.products.*']],
                    ['route' => 'admin.orders.index', 'icon' => 'shopping_cart', 'label' => 'Đơn hàng', 'match' => ['admin.orders.*']],
                    ['route' => 'admin.users.index', 'icon' => 'group', 'label' => 'Người dùng', 'match' => ['admin.users.*']],
                ];
            @endphp
            @foreach($nav as $item)
                @php
                    $active = false;
                    foreach ($item['match'] as $pattern) {
                        if (request()->routeIs($pattern)) { $active = true; break; }
                    }
                @endphp
                <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-4 py-3 font-headline text-xs font-bold uppercase tracking-widest transition-all duration-75
                    {{ $active
                        ? 'bg-primary-container text-white border-r-4 border-secondary-container shadow-[inset_0_0_20px_rgba(0,0,0,0.15)]'
                        : 'text-gray-400 hover:text-primary hover:bg-[#1c1c1c]' }}">
                    <span class="material-symbols-outlined text-xl shrink-0">{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
        <div class="p-4 mt-auto shrink-0 border-t border-[#1c1c1c]">
            <a href="{{ route('home') }}" class="flex w-full items-center justify-center py-3.5 bg-gradient-to-r from-primary-container to-secondary-container text-white font-headline text-[10px] font-black tracking-[0.2em] uppercase hover:brightness-110 transition-all active:scale-[0.98] shadow-[0_0_24px_rgba(123,47,247,0.25)]" title="Về cửa hàng">
                Về cửa hàng
            </a>
        </div>
        <footer class="p-4 bg-[#131313] space-y-1 border-t border-[#1c1c1c] shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-gray-400 hover:text-primary font-headline text-[10px] font-bold uppercase tracking-widest transition-colors">
                <span class="material-symbols-outlined text-sm">analytics</span>
                Phân tích nhanh
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-400 hover:text-primary font-headline text-[10px] font-bold uppercase tracking-widest transition-colors">
                <span class="material-symbols-outlined text-sm">history</span>
                Lịch sử đơn
            </a>
        </footer>
    </aside>

    {{-- Main: topbar + nội dung + footer (desktop giống code.html) --}}
    <div class="lg:ml-64 min-h-screen flex flex-col">
        {{-- TopNavBar --}}
        <header class="sticky top-0 z-40 flex flex-wrap justify-between items-center gap-y-2 w-full px-4 sm:px-6 min-h-16 py-2 bg-[#131313] shadow-[0_0_15px_rgba(123,47,247,0.1)] border-b border-outline-variant/10">
            @php
                $adminSearchIsCategories = request()->routeIs('admin.categories.*');
                $adminSearchIsProducts = request()->routeIs('admin.products.*');
                $adminSearchIsOrders = request()->routeIs('admin.orders.*');
                $adminSearchIsUsers = request()->routeIs('admin.users.*');
                $adminSearchAction = $adminSearchIsCategories
                    ? route('admin.categories.index')
                    : ($adminSearchIsOrders
                        ? route('admin.orders.index')
                        : ($adminSearchIsUsers
                            ? route('admin.users.index')
                            : route('admin.products.index')));
                $adminSearchPlaceholder = $adminSearchIsCategories
                    ? 'Tìm danh mục...'
                    : ($adminSearchIsProducts
                        ? 'Tìm sản phẩm trong kho...'
                        : ($adminSearchIsOrders
                            ? 'Truy vấn đơn hàng (mã, SĐT, email)...'
                            : ($adminSearchIsUsers ? 'Lệnh tìm người dùng (tên, email, ID)...' : 'Tìm kiếm hệ thống...')));
            @endphp
            <div class="flex flex-wrap items-center gap-y-2 gap-x-3 sm:gap-6 min-w-0 flex-1">
                <button type="button" id="admin-sidebar-open" class="lg:hidden p-2 text-primary hover:bg-primary-container/15 shrink-0 -ml-1" aria-label="Mở menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <span class="text-base sm:text-xl font-black text-primary tracking-[0.12em] sm:tracking-[0.2em] font-headline uppercase truncate">Quản trị cửa hàng</span>
                <nav class="hidden lg:flex items-center gap-1 font-headline text-[11px] uppercase tracking-tight shrink-0" aria-label="Lối tắt quản trị">
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-secondary-container border-b-2 border-secondary-container' : 'text-gray-500 hover:text-primary hover:bg-[#7b2ff7]/10' }}">Tổng quan</a>
                    <a href="{{ route('admin.categories.index') }}" class="px-3 py-1.5 transition-colors {{ request()->routeIs('admin.categories.*') ? 'text-secondary-container border-b-2 border-secondary-container' : 'text-gray-500 hover:text-primary hover:bg-[#7b2ff7]/10' }}">Danh mục</a>
                    <a href="{{ route('admin.orders.index') }}" class="px-3 py-1.5 transition-colors {{ request()->routeIs('admin.orders.*') ? 'text-secondary-container border-b-2 border-secondary-container' : 'text-gray-500 hover:text-primary hover:bg-[#7b2ff7]/10' }}">Đơn hàng</a>
                </nav>
                <form action="{{ $adminSearchAction }}" method="get" class="hidden md:flex relative group max-w-[16rem] flex-1 min-w-0">
                    @if(($adminSearchIsOrders ?? false) && request()->filled('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 pointer-events-none">
                        <span class="material-symbols-outlined text-sm">search</span>
                    </span>
                    <input type="search" name="q" value="{{ request('q') }}" class="bg-[#1c1c1c] border-none text-[11px] font-headline tracking-widest text-primary placeholder:text-gray-600 pl-10 pr-3 py-2 w-full max-w-xs focus:ring-1 focus:ring-secondary-container transition-all" placeholder="{{ $adminSearchPlaceholder }}" autocomplete="off">
                </form>
            </div>
            @php
                $topActive = function (string $name): string {
                    return request()->routeIs($name)
                        ? 'text-secondary-container bg-primary-container/20 ring-1 ring-secondary-container/35'
                        : 'text-gray-500 hover:bg-primary-container/10 hover:text-primary';
                };
            @endphp
            <div class="flex items-center gap-1 sm:gap-2 shrink-0">
                <a href="{{ route('admin.notifications') }}" class="p-2 transition-colors inline-flex rounded-none {{ $topActive('admin.notifications') }}" title="Thông báo">
                    <span class="material-symbols-outlined text-xl">notifications</span>
                </a>
                <a href="{{ route('admin.terminal') }}" class="p-2 transition-colors inline-flex rounded-none {{ $topActive('admin.terminal') }}" title="Dòng lệnh">
                    <span class="material-symbols-outlined text-xl">terminal</span>
                </a>
                <a href="{{ route('admin.settings') }}" class="p-2 transition-colors inline-flex rounded-none {{ $topActive('admin.settings') }}" title="Cài đặt">
                    <span class="material-symbols-outlined text-xl">settings</span>
                </a>
                <a href="{{ route('admin.profile.edit') }}" class="h-9 w-9 sm:h-8 sm:w-8 bg-primary-container border overflow-hidden flex items-center justify-center text-xs font-headline font-bold text-white shrink-0 transition-all {{ request()->routeIs('admin.profile.*') ? 'border-secondary-container ring-2 ring-secondary-container/50' : 'border-secondary-container/50 hover:opacity-90' }}" title="Thông tin cá nhân (quản trị)">
                    {{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </a>
            </div>
        </header>

        <div class="flex-1 flex flex-col p-4 sm:p-6 lg:p-8 space-y-6 lg:space-y-8 overflow-x-hidden bg-[#131313] relative">
            <div class="absolute top-[10%] right-[-5%] w-[280px] h-[280px] bg-primary-container/8 blur-[100px] rounded-full pointer-events-none"></div>
            <div class="absolute bottom-[5%] left-[0%] w-[200px] h-[200px] bg-secondary-container/6 blur-[80px] rounded-full pointer-events-none"></div>

            @if(session('success'))
                <div class="relative z-10 px-4 py-3 border border-secondary-container/40 text-secondary-container text-sm font-headline">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="relative z-10 px-4 py-3 border border-red-400/40 text-red-300 text-sm font-headline">{{ session('error') }}</div>
            @endif

            <div class="relative z-10 flex-1">
                @yield('content')
            </div>
        </div>

        <footer class="flex flex-col sm:flex-row justify-between items-center gap-3 px-6 sm:px-8 py-3 bg-[#0e0e0e] border-t border-[#1c1c1c] text-[10px]">
            <span class="font-body text-gray-600 uppercase tracking-[0.25em] sm:tracking-[0.3em] text-center sm:text-left">© {{ date('Y') }} {{ config('app.name') }} — Bảng quản trị</span>
            <div class="flex flex-wrap justify-center gap-4 sm:gap-6">
                <span class="font-body text-gray-600 uppercase tracking-[0.3em] cursor-default">Tài liệu API</span>
                <span class="font-body text-secondary-container underline uppercase tracking-[0.3em] cursor-default">Trạng thái hệ thống</span>
                <span class="font-body text-gray-600 uppercase tracking-[0.3em] cursor-default">Bảo mật bật</span>
            </div>
        </footer>
    </div>

    <script>
        (function () {
            var side = document.getElementById('admin-sidebar');
            var openBtn = document.getElementById('admin-sidebar-open');
            var overlay = document.getElementById('admin-sidebar-overlay');
            function openMenu() {
                side.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
            function closeMenu() {
                side.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
            openBtn && openBtn.addEventListener('click', openMenu);
            overlay && overlay.addEventListener('click', closeMenu);
            document.querySelectorAll('#admin-sidebar a').forEach(function (a) {
                a.addEventListener('click', function () { if (window.innerWidth < 1024) closeMenu(); });
            });
        })();
    </script>
    @include('partials.synth-confirm-modal')
    @stack('scripts')
</body>
</html>

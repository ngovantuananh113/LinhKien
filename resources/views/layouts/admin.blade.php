<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Quản trị') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --surface: #0e0e0e;
            --panel: #1a1a1a;
            --primary: #d2bbff;
            --accent: #00f4fe;
        }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; font-family: 'Manrope', sans-serif; background: var(--surface); color: #e6feff; }
        h1, h2, .font-display { font-family: 'Space Grotesk', sans-serif; }
        .admin-wrap { display: grid; grid-template-columns: 220px 1fr; min-height: 100vh; }
        .admin-nav {
            background: var(--panel);
            padding: 1rem;
            border-right: 1px solid rgba(0, 244, 254, 0.15);
        }
        .admin-nav a { display: block; padding: 0.5rem 0; color: var(--primary); }
        .admin-nav a:hover { color: var(--accent); }
        .admin-content { padding: 1.5rem; }
        table.data-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        table.data-table th, table.data-table td { border: 1px solid rgba(0,244,254,0.15); padding: 0.5rem 0.75rem; text-align: left; }
        .alert { padding: 0.75rem 1rem; margin-bottom: 1rem; border: 1px solid rgba(0,244,254,0.3); }
        .alert-error { border-color: rgba(255,180,171,0.5); color: #ffb4ab; }
        input, select, textarea { background: #131313; border: 1px solid rgba(0,244,254,0.2); color: #e6feff; padding: 0.5rem; font-family: inherit; }
        .btn { display: inline-block; padding: 0.4rem 0.8rem; border: 1px solid rgba(0,244,254,0.3); color: var(--primary); cursor: pointer; background: transparent; font-family: inherit; }
        .btn:hover { color: var(--accent); }
        @media (max-width: 768px) {
            .admin-wrap { grid-template-columns: 1fr; }
            .admin-nav { display: flex; flex-wrap: wrap; gap: 0.5rem; border-right: none; border-bottom: 1px solid rgba(0, 244, 254, 0.15); }
        }
        .pagination { display: flex; gap: 0.35rem; list-style: none; padding: 0; margin: 1rem 0; flex-wrap: wrap; align-items: center; }
        .page-link { color: var(--primary) !important; border: 1px solid rgba(0,244,254,0.2) !important; background: transparent !important; }
        .page-item.active .page-link { color: var(--accent) !important; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-wrap">
        <aside class="admin-nav">
            <div class="font-display" style="font-size:1.1rem; margin-bottom:1rem;">Quản trị</div>
            <a href="{{ route('admin.dashboard') }}">Tổng quan</a>
            <a href="{{ route('admin.categories.index') }}">Danh mục</a>
            <a href="{{ route('admin.products.index') }}">Sản phẩm</a>
            <a href="{{ route('admin.orders.index') }}">Đơn hàng</a>
            <a href="{{ route('admin.users.index') }}">Người dùng</a>
            <a href="{{ route('home') }}">← Cửa hàng</a>
        </aside>
        <div class="admin-content">
            @if(session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </div>
    @stack('scripts')
</body>
</html>

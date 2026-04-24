<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Linh kiện máy tính') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --surface: #131313;
            --surface-container-low: #1a1a1a;
            --surface-container-highest: #242424;
            --primary: #d2bbff;
            --primary-container: #7b2ff7;
            --secondary: #e6feff;
            --secondary-container: #00f4fe;
            --outline-variant: rgba(255, 255, 255, 0.12);
            --error: #ffb4ab;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', system-ui, sans-serif;
            background: var(--surface);
            color: var(--secondary);
        }
        h1, h2, h3, .font-display {
            font-family: 'Space Grotesk', system-ui, sans-serif;
        }
        a { color: var(--primary); text-decoration: none; }
        a:hover { color: var(--secondary-container); }
        .site-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            padding: 1rem 1.5rem;
            background: var(--surface-container-low);
            border-bottom: 1px solid rgba(0, 244, 254, 0.2);
        }
        .site-nav { display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; }
        .site-main { padding: 1.5rem; max-width: 1200px; margin: 0 auto; }
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border: 1px solid var(--outline-variant);
            background: var(--surface-container-highest);
            color: var(--secondary);
            font-family: inherit;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .btn:hover { border-color: var(--secondary-container); color: var(--primary); }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-container), #004f53);
            border-color: var(--secondary-container);
            color: var(--secondary);
        }
        .alert { padding: 0.75rem 1rem; margin-bottom: 1rem; border: 1px solid var(--outline-variant); }
        .alert-success { border-color: rgba(0, 244, 254, 0.4); color: var(--secondary-container); }
        .alert-error { border-color: rgba(255, 180, 171, 0.5); color: var(--error); }
        table.data-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        table.data-table th, table.data-table td {
            border: 1px solid var(--outline-variant);
            padding: 0.5rem 0.75rem;
            text-align: left;
        }
        table.data-table th { background: var(--surface-container-low); }
        input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="tel"], select, textarea {
            width: 100%; max-width: 32rem;
            padding: 0.5rem 0.75rem;
            background: var(--surface-container-low);
            border: 1px solid var(--outline-variant);
            color: var(--secondary);
            font-family: inherit;
        }
        label { display: block; margin-bottom: 0.25rem; font-size: 0.85rem; color: var(--primary); }
        .field { margin-bottom: 1rem; }
        .pagination { display: flex; gap: 0.35rem; list-style: none; padding: 0; margin: 1rem 0; flex-wrap: wrap; align-items: center; }
        .pagination li span, .pagination li a, .page-link {
            display: inline-block;
            padding: 0.35rem 0.65rem;
            border: 1px solid var(--outline-variant);
            color: var(--secondary);
        }
        .pagination li.active span, .page-item.active .page-link { background: var(--surface-container-highest); color: var(--primary); }
        .pagination li a:hover, .page-link:hover { border-color: var(--secondary-container); }
        .page-item.disabled .page-link { opacity: 0.45; pointer-events: none; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; }
        .card {
            border: 1px solid var(--outline-variant);
            padding: 1rem;
            background: var(--surface-container-low);
        }
        .card img { width: 100%; height: 160px; object-fit: cover; background: #000; }
        .muted { opacity: 0.75; font-size: 0.85rem; }
    </style>
    @stack('styles')
</head>
<body>
    <header class="site-header">
        <a href="{{ route('home') }}" class="font-display" style="font-size:1.25rem;">LINH KIỆN</a>
        <nav class="site-nav">
            <a href="{{ route('products.index') }}">Sản phẩm</a>
            @auth
                <a href="{{ route('cart.index') }}">Giỏ hàng</a>
                <a href="{{ route('orders.index') }}">Đơn hàng</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">Quản trị</a>
                @endif
                <span class="muted">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="post" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn" style="padding:0.35rem 0.75rem;">Đăng xuất</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Đăng ký</a>
            @endauth
        </nav>
    </header>
    <main class="site-main">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>

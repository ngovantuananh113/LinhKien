<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    {{-- Cho phép chuyển trang mượt (Chrome 126+): login ↔ đăng ký --}}
    <meta name="view-transition" content="same-origin">
    <title>@yield('title') — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface": "#131313",
                        "outline-variant": "#4a4456",
                        "outline": "#958da2",
                        "primary": "#d2bbff",
                        "primary-container": "#7b2ff7",
                        "on-surface": "#e5e2e1",
                        "on-surface-variant": "#ccc3d9",
                        "secondary-fixed": "#63f7ff",
                        "secondary-container": "#00f4fe",
                        "surface-container": "#201f1f",
                        "surface-container-lowest": "#0e0e0e",
                        "surface-container-low": "#1c1b1b",
                        "surface-container-highest": "#353534",
                        "on-primary-container": "#ebddff",
                        "on-secondary-fixed": "#002021",
                    },
                    fontFamily: {
                        "headline": ["Space Grotesk", "sans-serif"],
                        "body": ["Manrope", "sans-serif"],
                    },
                    borderRadius: { "DEFAULT": "0px", "lg": "0px", "xl": "0px", "full": "9999px" },
                },
            },
        };
    </script>
    <style>
        html {
            overflow-x: clip;
            -webkit-overflow-scrolling: touch;
        }
        .auth-page-root {
            min-height: 100vh;
            min-height: 100dvh;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-card {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(53, 53, 52, 0.6);
            clip-path: polygon(0 0, 95% 0, 100% 5%, 100% 100%, 5% 100%, 0 95%);
        }
        .neon-glow-purple {
            box-shadow: 0 0 20px rgba(210, 187, 255, 0.15);
        }
        .chamfer-card {
            clip-path: polygon(0% 0%, 92% 0%, 100% 8%, 100% 100%, 8% 100%, 0% 92%);
        }
        @media (max-width: 639px) {
            .glass-card {
                clip-path: polygon(0 0, 98% 0, 100% 3%, 100% 100%, 3% 100%, 0 98%);
            }
            .chamfer-card {
                clip-path: polygon(0% 0%, 96% 0%, 100% 5%, 100% 100%, 5% 100%, 0% 96%);
            }
        }
        .circuit-line {
            position: absolute;
            background: linear-gradient(90deg, transparent, #7b2ff7, transparent);
            height: 1px;
            width: 100%;
            opacity: 0.3;
        }
        .circuit-line-v {
            position: absolute;
            background: linear-gradient(180deg, transparent, #00f4fe, transparent);
            width: 1px;
            height: 100%;
            opacity: 0.3;
        }
        .touch-target {
            min-height: 2.75rem;
        }
        @media (min-width: 640px) {
            .touch-target {
                min-height: auto;
            }
        }

        /* Khung form cố định — login & đăng ký cùng chiều rộng */
        .auth-form-shell {
            width: 100%;
            max-width: 26rem; /* 416px */
            margin-left: auto;
            margin-right: auto;
        }

        /* Vào trang: fade + trượt nhẹ + bỏ blur */
        @keyframes auth-enter {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .auth-stage {
            animation: auth-enter 0.42s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @media (prefers-reduced-motion: reduce) {
            .auth-stage {
                animation: none;
            }
        }

        /* Chuyển trang giữa login / register (View Transitions API) */
        @supports (view-transition-name: none) {
            ::view-transition-old(root),
            ::view-transition-new(root) {
                animation-duration: 0.42s;
                animation-timing-function: cubic-bezier(0.33, 1, 0.68, 1);
            }
            ::view-transition-old(root) {
                animation-name: auth-vt-out;
            }
            ::view-transition-new(root) {
                animation-name: auth-vt-in;
            }
        }
        @keyframes auth-vt-out {
            from { opacity: 1; }
            to {
                opacity: 0;
                transform: translateX(-10px) scale(0.992);
                filter: blur(6px);
            }
        }
        @keyframes auth-vt-in {
            from {
                opacity: 0;
                transform: translateX(12px) scale(0.992);
                filter: blur(6px);
            }
            to { opacity: 1; }
        }
        @media (prefers-reduced-motion: reduce) {
            @supports (view-transition-name: none) {
                ::view-transition-old(root),
                ::view-transition-new(root) {
                    animation-duration: 0.01ms !important;
                }
            }
        }

        /* Nền chung auth — cùng lưới với thiết kế login */
        body.auth-page-root {
            background-color: #131313;
            background-image:
                linear-gradient(rgba(123, 47, 247, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(123, 47, 247, 0.05) 1px, transparent 1px);
            background-size: 28px 28px;
        }
        @media (min-width: 640px) {
            body.auth-page-root {
                background-size: 40px 40px;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="@yield('body_class', 'font-body text-on-surface min-h-screen')">
@yield('content')
@stack('scripts')
<script>
(function () {
    document.addEventListener('click', function (e) {
        var a = e.target.closest('a[href]');
        if (!a || a.getAttribute('href') === '#' || a.target === '_blank' || a.hasAttribute('download')) return;
        var href = a.getAttribute('href');
        if (!href || (href.indexOf('/login') === -1 && href.indexOf('/register') === -1)) return;
        try {
            var u = new URL(a.href, window.location.href);
            if (u.origin !== window.location.origin) return;
        } catch (err) { return; }
        if (typeof document.startViewTransition !== 'function') return;
        e.preventDefault();
        document.startViewTransition(function () {
            window.location.href = a.href;
        });
    }, false);
})();
</script>
</body>
</html>

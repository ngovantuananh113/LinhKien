@extends('layouts.shop-synth')

@section('title', 'Linh kiện')

@push('head')
<style>
    @keyframes catalog-fade-up {
        from { opacity: 0; transform: translateY(18px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes catalog-line-glow {
        0%, 100% { opacity: 0.4; }
        50% { opacity: 1; }
    }
    .catalog-enter {
        animation: catalog-fade-up 0.55s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        opacity: 0;
    }
    .catalog-enter-delay-1 { animation-delay: 0.08s; }
    .catalog-card-enter {
        animation: catalog-fade-up 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        opacity: 0;
    }
    .catalog-pagination-enter {
        animation: catalog-fade-up 0.6s cubic-bezier(0.22, 1, 0.36, 1) 0.15s forwards;
        opacity: 0;
    }
    .circuit-line-catalog-anim {
        animation: catalog-line-glow 2.5s ease-in-out infinite;
    }
    .catalog-range::-webkit-slider-thumb {
        -webkit-appearance: none;
        height: 16px;
        width: 16px;
        background: #00f4fe;
        cursor: pointer;
        box-shadow: 0 0 10px #00f4fe;
    }
    .catalog-range::-moz-range-thumb {
        height: 16px;
        width: 16px;
        background: #00f4fe;
        cursor: pointer;
        border: none;
        box-shadow: 0 0 10px #00f4fe;
    }
    .circuit-line-catalog {
        background: linear-gradient(90deg, transparent, #00f4fe, transparent);
        height: 1px;
        width: 100%;
    }
    .catalog-sort-menu.is-open {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }
    .catalog-sort-chevron.is-open {
        transform: rotate(180deg);
    }
    #catalog-root.is-loading {
        opacity: 0.72;
        pointer-events: none;
        transition: opacity 0.2s ease;
    }
</style>
@endpush

@section('content')
<div id="catalog-root" class="catalog-enter w-full pb-16 overflow-x-clip lg:-mt-2">
    @include('user.products.partials.catalog-inner')
</div>
@endsection

@push('scripts')
<script>
(function () {
    var productsUrl = @json(route('products.index'));
    var root = document.getElementById('catalog-root');

    function formatPrice(n) {
        return new Intl.NumberFormat('vi-VN').format(n) + ' đ';
    }

    function syncPriceLabel(rangeEl, labelEl) {
        if (!rangeEl || !labelEl) return;
        labelEl.textContent = formatPrice(parseInt(rangeEl.value, 10) || 0);
    }

    function pushStateClean(form) {
        try {
            var u = new URL(form.action, window.location.origin);
            var p = new URLSearchParams(new FormData(form));
            p.delete('partial');
            u.search = p.toString();
            history.pushState({}, '', u.pathname + u.search);
        } catch (e) {}
    }

    function fetchCatalog(url) {
        if (!root) return;
        root.classList.add('is-loading');
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            },
            credentials: 'same-origin'
        })
            .then(function (r) {
                if (!r.ok) throw new Error('Network');
                return r.text();
            })
            .then(function (html) {
                root.innerHTML = html;
                root.classList.remove('is-loading');
                initCatalogUi(root);
                var form = document.getElementById('catalog-filter');
                if (form) pushStateClean(form);
            })
            .catch(function () {
                root.classList.remove('is-loading');
                try {
                    var u = new URL(url, window.location.origin);
                    u.searchParams.delete('partial');
                    window.location.href = u.pathname + u.search;
                } catch (e2) {
                    window.location.href = productsUrl;
                }
            });
    }

    function buildPartialUrlFromForm(form) {
        var u = new URL(form.action, window.location.origin);
        var p = new URLSearchParams(new FormData(form));
        p.set('partial', '1');
        u.search = p.toString();
        return u.toString();
    }

    function openSortMenu(wrap, open) {
        var menu = wrap.querySelector('#catalog-sort-menu');
        var btn = wrap.querySelector('#catalog-sort-toggle');
        var chev = wrap.querySelector('.catalog-sort-chevron');
        if (!menu || !btn) return;
        if (open) {
            menu.classList.remove('hidden');
            requestAnimationFrame(function () {
                menu.classList.add('is-open');
                if (chev) chev.classList.add('is-open');
            });
            btn.setAttribute('aria-expanded', 'true');
        } else {
            menu.classList.remove('is-open');
            if (chev) chev.classList.remove('is-open');
            btn.setAttribute('aria-expanded', 'false');
            setTimeout(function () { menu.classList.add('hidden'); }, 180);
        }
    }

    function initCatalogUi(container) {
        var form = container.querySelector('#catalog-filter');
        var range = container.querySelector('#catalog-price-range');
        var label = container.querySelector('#catalog-price-label');
        if (range && label) {
            range.addEventListener('input', function () {
                syncPriceLabel(range, label);
            });
        }

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                fetchCatalog(buildPartialUrlFromForm(form));
            });
        }

        var clearBtn = container.querySelector('#catalog-clear-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', function (e) {
                e.preventDefault();
                var u = new URL(productsUrl, window.location.origin);
                u.searchParams.set('partial', '1');
                fetchCatalog(u.toString());
            });
        }

        container.querySelectorAll('.catalog-inline-clear').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var u = new URL(productsUrl, window.location.origin);
                u.searchParams.set('partial', '1');
                fetchCatalog(u.toString());
            });
        });

        var sortWrap = container.querySelector('[data-catalog-sort-wrap]');
        if (sortWrap) {
            var toggle = sortWrap.querySelector('#catalog-sort-toggle');
            var menu = sortWrap.querySelector('#catalog-sort-menu');
            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                var open = !menu.classList.contains('is-open');
                openSortMenu(sortWrap, open);
            });
            sortWrap.querySelectorAll('.catalog-sort-option').forEach(function (opt) {
                opt.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var val = opt.getAttribute('data-sort-value') || '';
                    var hid = container.querySelector('#catalog-sort-input');
                    if (hid) hid.value = val;
                    openSortMenu(sortWrap, false);
                    var f = container.querySelector('#catalog-filter');
                    if (f) fetchCatalog(buildPartialUrlFromForm(f));
                });
            });
        }

        container.querySelectorAll('.synth-pagination a[href]').forEach(function (a) {
            var href = a.getAttribute('href');
            if (!href || href.indexOf('/products') === -1) return;
            a.addEventListener('click', function (e) {
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;
                e.preventDefault();
                var u = new URL(href, window.location.origin);
                u.searchParams.set('partial', '1');
                fetchCatalog(u.toString());
            });
        });
    }

    document.addEventListener('click', function (e) {
        if (e.target.closest('[data-catalog-sort-wrap]')) return;
        document.querySelectorAll('[data-catalog-sort-wrap]').forEach(function (wrap) {
            openSortMenu(wrap, false);
        });
    });

    if (root) initCatalogUi(root);
})();
</script>
@endpush

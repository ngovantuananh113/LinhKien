@extends('layouts.admin-synth')

@section('title', 'Quản lý sản phẩm')

@push('head')
    <style>
        @keyframes admin-inv-fade-up {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes admin-inv-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(123, 47, 247, 0); }
            50% { box-shadow: 0 0 28px -4px rgba(123, 47, 247, 0.35); }
        }
        .admin-inv-hero { animation: admin-inv-fade-up 0.65s ease-out both; }
        .admin-inv-stat { animation: admin-inv-fade-up 0.55s ease-out both; }
        .admin-inv-stat:nth-child(1) { animation-delay: 0.08s; }
        .admin-inv-stat:nth-child(2) { animation-delay: 0.16s; }
        .admin-inv-form { animation: admin-inv-fade-up 0.7s ease-out 0.12s both; }
        .admin-inv-table { animation: admin-inv-fade-up 0.7s ease-out 0.18s both; }
        .admin-inv-row {
            opacity: 0;
            animation: admin-inv-fade-up 0.42s ease-out forwards;
        }
        .admin-inv-pulse-stat { animation: admin-inv-glow 4s ease-in-out infinite; }
        .admin-category-select:focus-within .admin-category-glow {
            opacity: 1;
        }
        .admin-category-select select option { background: #201f1f; color: #e5e2e1; padding: 0.5rem; }
        #admin-product-toast.admin-toast-show { opacity: 1; transform: translateY(0); pointer-events: auto; }
    </style>
@endpush

@section('content')
    <div id="admin-product-toast" class="fixed bottom-6 right-6 z-[100] max-w-sm px-4 py-3 bg-surface-container-high border border-secondary-container/40 text-secondary-container text-sm font-headline shadow-[0_0_32px_rgba(0,244,254,0.2)] opacity-0 translate-y-2 pointer-events-none transition-all duration-300" role="status" aria-live="polite"></div>

    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-8 lg:mb-12 admin-inv-hero">
        <div>
            <h1 class="font-headline text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-white">Quản lý kho sản phẩm</h1>
            <p class="mt-2 text-on-surface-variant font-headline text-xs sm:text-sm tracking-wide">Đơn vị: Kho linh kiện // Đăng ký: Hệ thống cửa hàng</p>
        </div>
        <div class="flex flex-wrap gap-3 sm:gap-4">
            <div class="admin-inv-stat admin-inv-pulse-stat flex items-center gap-2 px-4 py-2.5 bg-surface-container-low border-b-2 border-primary rounded-none">
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Tổng tồn kho</span>
                <span id="admin-stat-global-stock" class="text-lg sm:text-xl font-headline font-bold text-primary tabular-nums">{{ number_format($globalStock) }}</span>
            </div>
            <div class="admin-inv-stat flex items-center gap-2 px-4 py-2.5 bg-surface-container-low border-b-2 border-secondary-container rounded-none">
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Số mặt hàng</span>
                <span id="admin-stat-active-sku" class="text-lg sm:text-xl font-headline font-bold text-secondary-container tabular-nums">{{ number_format($activeSku) }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <section id="product-form-panel" class="lg:col-span-4 admin-inv-form scroll-mt-24"
            data-fragments-url="{{ route('admin.products.fragments') }}">
            <div id="product-form-fragment">
                @include('admin.products.partials.form')
            </div>
        </section>

        <section class="lg:col-span-8 admin-inv-table">
            <div id="product-table-fragment">
                @include('admin.products.partials.table')
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var fragmentsBase = @json(route('admin.products.fragments'));
            var csrf = document.querySelector('meta[name="csrf-token"]');
            var csrfToken = csrf ? csrf.getAttribute('content') : '';

            function fmtInt(n) {
                try { return new Intl.NumberFormat('vi-VN').format(n); } catch (e) { return String(n); }
            }

            function showToast(msg, isErr) {
                var el = document.getElementById('admin-product-toast');
                if (!el || !msg) return;
                el.textContent = msg;
                el.classList.toggle('border-red-400/50', !!isErr);
                el.classList.toggle('text-red-300', !!isErr);
                el.classList.toggle('text-secondary-container', !isErr);
                el.classList.add('admin-toast-show');
                clearTimeout(el._t);
                el._t = setTimeout(function () { el.classList.remove('admin-toast-show'); }, 4200);
            }

            function mergeQuery(base, extra) {
                var u = new URL(base, window.location.origin);
                Object.keys(extra || {}).forEach(function (k) {
                    var v = extra[k];
                    if (v !== undefined && v !== null && v !== '') u.searchParams.set(k, v);
                    else u.searchParams.delete(k);
                });
                return u.toString();
            }

            function currentListQuery() {
                var p = new URLSearchParams(window.location.search);
                var o = {};
                ['q', 'page', 'edit'].forEach(function (k) {
                    if (p.has(k)) o[k] = p.get(k);
                });
                return o;
            }

            function replaceUrl(queryObj) {
                var u = new URL(window.location.href);
                u.search = '';
                Object.keys(queryObj || {}).forEach(function (k) {
                    var v = queryObj[k];
                    if (v !== undefined && v !== null && v !== '') u.searchParams.set(k, v);
                });
                window.history.replaceState({}, '', u.pathname + u.search);
            }

            function applyPayload(data) {
                var ff = document.getElementById('product-form-fragment');
                var tf = document.getElementById('product-table-fragment');
                if (data.form && ff) ff.innerHTML = data.form;
                if (data.table && tf) tf.innerHTML = data.table;
                if (typeof data.globalStock === 'number') {
                    var gs = document.getElementById('admin-stat-global-stock');
                    if (gs) gs.textContent = fmtInt(data.globalStock);
                }
                if (typeof data.activeSku === 'number') {
                    var ak = document.getElementById('admin-stat-active-sku');
                    if (ak) ak.textContent = fmtInt(data.activeSku);
                }
                bindDynamic();
            }

            function fetchFragments(queryObj, opts) {
                opts = opts || {};
                var url = mergeQuery(fragmentsBase, queryObj);
                return fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-Product-Fragments': '1'
                    },
                    credentials: 'same-origin'
                }).then(function (r) {
                    return r.json().then(function (j) {
                        return { ok: r.ok, status: r.status, body: j };
                    });
                }).catch(function () {
                    return { ok: false, status: 0, body: {} };
                });
            }

            function bindImageFilename() {
                var input = document.getElementById('product-image');
                var out = document.getElementById('product-image-filename');
                if (input && out) {
                    input.onchange = function () {
                        out.textContent = input.files && input.files[0] ? input.files[0].name : '';
                    };
                }
            }

            function bindFormSubmit() {
                var form = document.getElementById('admin-product-form');
                if (!form || form.dataset.ajaxBound) return;
                form.dataset.ajaxBound = '1';
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    var fd = new FormData(form);
                    var action = form.getAttribute('action');
                    fetch(action, {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-Product-Fragments': '1',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        credentials: 'same-origin'
                    }).then(function (r) {
                        return r.json().then(function (j) { return { ok: r.ok, status: r.status, body: j }; });
                    }).then(function (res) {
                        if (res.status === 422 && res.body.form) {
                            var ff = document.getElementById('product-form-fragment');
                            if (ff) ff.innerHTML = res.body.form;
                            bindDynamic();
                            showToast(res.body.message || 'Vui lòng kiểm tra lại dữ liệu.', true);
                            return;
                        }
                        if (!res.ok || !res.body.ok) {
                            showToast((res.body && res.body.message) || 'Có lỗi xảy ra.', true);
                            return;
                        }
                        applyPayload(res.body);
                        var q = {
                            q: fd.get('return_q') || undefined,
                            page: fd.get('return_page') || undefined
                        };
                        replaceUrl(q);
                        showToast(res.body.message || 'Đã lưu.', false);
                    }).catch(function () {
                        showToast('Không kết nối được máy chủ.', true);
                    });
                });
            }

            function bindCancel() {
                var btn = document.getElementById('product-form-cancel');
                if (!btn || btn.dataset.ajaxBound) return;
                btn.dataset.ajaxBound = '1';
                btn.addEventListener('click', function () {
                    var cur = currentListQuery();
                    delete cur.edit;
                    fetchFragments(cur).then(function (res) {
                        if (!res.ok) { showToast('Không tải được form.', true); return; }
                        applyPayload(res.body);
                        replaceUrl(cur);
                    });
                });
            }

            function bindEditLinks() {
                document.querySelectorAll('a.js-product-edit[href]').forEach(function (a) {
                    if (a.dataset.ajaxBound) return;
                    a.dataset.ajaxBound = '1';
                    a.addEventListener('click', function (e) {
                        e.preventDefault();
                        try {
                            var u = new URL(a.getAttribute('href'), window.location.origin);
                            var q = { q: u.searchParams.get('q') || undefined, page: u.searchParams.get('page') || undefined, edit: u.searchParams.get('edit') };
                            fetchFragments(q).then(function (res) {
                                if (!res.ok) { showToast('Không tải được dữ liệu.', true); return; }
                                applyPayload(res.body);
                                replaceUrl(q);
                                var panel = document.getElementById('product-form-panel');
                                if (panel) panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            });
                        } catch (err) {}
                    });
                });
            }

            function bindDeleteForms() {
                document.querySelectorAll('form.js-product-delete-form').forEach(function (form) {
                    if (form.dataset.ajaxBound) return;
                    form.dataset.ajaxBound = '1';
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        function runDelete() {
                        var fd = new FormData(form);
                        fetch(form.getAttribute('action'), {
                            method: 'POST',
                            body: fd,
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-Product-Fragments': '1',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            credentials: 'same-origin'
                        }).then(function (r) {
                            return r.json().then(function (j) { return { ok: r.ok, status: r.status, body: j }; });
                        }).then(function (res) {
                            if (res.status === 422 && res.body && res.body.message) {
                                showToast(res.body.message, true);
                                return;
                            }
                            if (!res.ok || (res.body && res.body.ok === false)) {
                                showToast((res.body && res.body.message) || 'Không xóa được.', true);
                                return;
                            }
                            if (res.body && res.body.ok) {
                                applyPayload(res.body);
                                var cur = currentListQuery();
                                replaceUrl(cur);
                                showToast(res.body.message || 'Đã xóa.', false);
                            }
                        }).catch(function () { showToast('Lỗi mạng.', true); });
                        }
                        if (window.SynthConfirm && typeof window.SynthConfirm.open === 'function') {
                            window.SynthConfirm.open({
                                title: 'Xóa sản phẩm?',
                                message: 'Sản phẩm sẽ bị gỡ khỏi kho quản trị. Thao tác không thể hoàn tác từ giao diện này.',
                                onConfirm: runDelete
                            });
                        } else {
                            if (window.confirm('Xóa sản phẩm này?')) runDelete();
                        }
                    });
                });
            }

            function bindPagination() {
                document.querySelectorAll('#product-table-fragment .admin-product-pagination a[href]').forEach(function (a) {
                    if (a.dataset.ajaxBound) return;
                    a.dataset.ajaxBound = '1';
                    a.addEventListener('click', function (e) {
                        e.preventDefault();
                        try {
                            var u = new URL(a.getAttribute('href'), window.location.origin);
                            var q = { q: u.searchParams.get('q') || undefined, page: u.searchParams.get('page') || '1', edit: u.searchParams.get('edit') || undefined };
                            fetchFragments(q).then(function (res) {
                                if (!res.ok) { showToast('Không tải được trang danh sách.', true); return; }
                                applyPayload(res.body);
                                replaceUrl(q);
                            });
                        } catch (err) {}
                    });
                });
            }

            function bindDynamic() {
                bindImageFilename();
                bindFormSubmit();
                bindCancel();
                bindEditLinks();
                bindDeleteForms();
                bindPagination();
            }

            bindDynamic();

            if (new URLSearchParams(window.location.search).get('edit')) {
                var panel = document.getElementById('product-form-panel');
                if (panel) requestAnimationFrame(function () { panel.scrollIntoView({ behavior: 'smooth', block: 'start' }); });
            }

            @if(session('success'))
            showToast(@json(session('success')), false);
            @endif
            @if(session('error'))
            showToast(@json(session('error')), true);
            @endif
        })();
    </script>
@endpush

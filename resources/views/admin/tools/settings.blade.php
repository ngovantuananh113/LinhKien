@extends('layouts.admin-synth')

@section('title', 'Cài đặt')

@section('content')
    <div class="max-w-2xl">
        <header class="mb-8">
            <div class="flex items-center gap-2 mb-2">
                <span class="h-1 w-8 bg-secondary-container shrink-0"></span>
                <span class="text-secondary-container font-headline text-[10px] tracking-[0.35em] uppercase font-bold">Hệ thống</span>
            </div>
            <h1 class="font-headline text-2xl sm:text-3xl font-black text-on-background uppercase tracking-tight">Cài đặt chung</h1>
            <p class="text-outline mt-2 text-sm font-body">Tùy chọn hiển thị và thông báo trong bảng quản trị (lưu cục bộ trình duyệt — demo).</p>
        </header>

        <div class="space-y-4 border border-outline-variant/20 bg-surface-container-lowest p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-outline-variant/10 pb-6">
                <div>
                    <p class="font-headline text-sm font-bold text-on-background">Âm thanh thông báo</p>
                    <p class="text-xs text-on-surface-variant mt-1">Phát tiếng khi có đơn mới (chỉ trình duyệt hỗ trợ).</p>
                </div>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="adm-set-sound" class="rounded border-outline-variant bg-surface-container-low text-primary-container focus:ring-secondary-container">
                    <span class="text-[11px] font-headline uppercase text-outline">Bật</span>
                </label>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-outline-variant/10 pb-6">
                <div>
                    <p class="font-headline text-sm font-bold text-on-background">Mật độ làm mới bảng</p>
                    <p class="text-xs text-on-surface-variant mt-1">Khoảng thời gian gợi ý khi mở trang đơn (giây).</p>
                </div>
                <select id="adm-set-refresh" class="bg-surface-container-low border border-outline-variant/35 px-3 py-2 text-sm text-on-background max-w-[12rem]">
                    <option value="0">Không tự làm mới</option>
                    <option value="30">30 giây</option>
                    <option value="60">60 giây</option>
                </select>
            </div>
            <div class="pt-2">
                <button type="button" id="adm-set-save" class="px-6 py-3 bg-gradient-to-r from-primary-container to-secondary-container text-white font-headline text-[11px] font-bold uppercase tracking-widest hover:brightness-110 transition-all">
                    Lưu vào trình duyệt
                </button>
                <p id="adm-set-msg" class="mt-3 text-xs text-secondary-container font-headline hidden">Đã lưu.</p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                var k = 'linhkien_admin_settings_v1';
                var sound = document.getElementById('adm-set-sound');
                var refresh = document.getElementById('adm-set-refresh');
                var btn = document.getElementById('adm-set-save');
                var msg = document.getElementById('adm-set-msg');
                try {
                    var o = JSON.parse(localStorage.getItem(k) || '{}');
                    if (sound) sound.checked = !!o.sound;
                    if (refresh) refresh.value = o.refresh != null ? String(o.refresh) : '0';
                } catch (e) {}
                btn && btn.addEventListener('click', function () {
                    try {
                        localStorage.setItem(k, JSON.stringify({
                            sound: sound ? sound.checked : false,
                            refresh: refresh ? parseInt(refresh.value, 10) || 0 : 0
                        }));
                        msg.classList.remove('hidden');
                        setTimeout(function () { msg.classList.add('hidden'); }, 2500);
                    } catch (e) {}
                });
            })();
        </script>
    @endpush
@endsection

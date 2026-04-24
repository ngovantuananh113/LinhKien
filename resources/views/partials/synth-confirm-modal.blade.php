{{-- Modal xác nhận xóa / thao tác nguy hiểm — dùng chung (admin + shop) --}}
<div id="synth-confirm-root" class="fixed inset-0 z-[200] hidden flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="synth-confirm-title" aria-hidden="true">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-[3px] synth-confirm-backdrop cursor-pointer" data-synth-confirm-backdrop tabindex="-1"></div>
    <div class="relative w-full max-w-[26rem] border border-outline-variant/35 bg-[#080808] shadow-[0_0_0_1px_rgba(0,244,254,0.08),0_28px_56px_rgba(0,0,0,0.75),0_0_80px_rgba(123,47,247,0.12)] overflow-hidden animate-[synth-confirm-in_0.22s_ease-out]">
        <div class="h-[3px] w-full bg-gradient-to-r from-primary-container via-secondary-container to-tertiary-container opacity-90"></div>
        <div class="absolute top-3 right-3">
            <button type="button" class="p-1.5 text-outline hover:text-on-background transition-colors" data-synth-confirm-cancel aria-label="Đóng">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>
        <div class="p-6 sm:p-8 pt-10">
            <div class="flex gap-4 sm:gap-5">
                <div class="shrink-0 w-14 h-14 flex items-center justify-center bg-[#1a0a0f] border border-error/40 text-error shadow-[inset_0_0_24px_rgba(255,180,171,0.06)]">
                    <span class="material-symbols-outlined text-3xl" style="font-variation-settings:'FILL'0,'wght'400">security_update_warning</span>
                </div>
                <div class="min-w-0 flex-1 pt-0.5">
                    <p class="text-[10px] font-headline font-bold uppercase tracking-[0.35em] text-secondary-container mb-2">Xác nhận hệ thống</p>
                    <h2 id="synth-confirm-title" class="font-headline text-lg sm:text-xl font-black uppercase tracking-tight text-on-background leading-tight">Xác nhận</h2>
                    <p id="synth-confirm-message" class="mt-3 text-sm text-on-surface-variant font-body leading-relaxed"></p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3 mt-8 sm:mt-10 justify-end">
                <button type="button" class="px-6 py-3 min-h-[44px] border border-outline-variant/45 text-on-surface-variant hover:text-on-background hover:border-secondary-container/50 font-headline text-[11px] font-bold uppercase tracking-[0.2em] transition-colors" data-synth-confirm-cancel>Hủy</button>
                <button type="button" class="px-6 py-3 min-h-[44px] bg-gradient-to-r from-[#6b1c2a] via-[#9a3545] to-[#5c1824] text-white font-headline text-[11px] font-black uppercase tracking-[0.2em] border border-error/35 shadow-[0_0_24px_rgba(255,180,171,0.2)] hover:brightness-110 active:scale-[0.98] transition-all" data-synth-confirm-ok>Xác nhận</button>
            </div>
        </div>
    </div>
</div>
<style>
    @keyframes synth-confirm-in {
        from { opacity: 0; transform: scale(0.97) translateY(8px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
</style>
<script>
(function () {
    var root = document.getElementById('synth-confirm-root');
    if (!root) return;
    var titleEl = document.getElementById('synth-confirm-title');
    var msgEl = document.getElementById('synth-confirm-message');
    var pendingForm = null;
    var pendingCallback = null;

    function close() {
        root.classList.add('hidden');
        root.setAttribute('aria-hidden', 'true');
        pendingForm = null;
        pendingCallback = null;
        document.body.style.overflow = '';
    }

    function openFromForm(form) {
        titleEl.textContent = form.getAttribute('data-confirm-title') || 'Xác nhận thao tác';
        msgEl.textContent = form.getAttribute('data-confirm-message') || '';
        pendingForm = form;
        pendingCallback = null;
        root.classList.remove('hidden');
        root.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form || form.nodeName !== 'FORM' || !form.hasAttribute('data-synth-confirm')) return;
        e.preventDefault();
        e.stopPropagation();
        openFromForm(form);
    }, true);

    function onConfirmClick() {
        if (pendingCallback) {
            var cb = pendingCallback;
            pendingCallback = null;
            close();
            cb();
            return;
        }
        if (pendingForm) {
            var f = pendingForm;
            pendingForm = null;
            close();
            f.submit();
        }
    }

    root.querySelectorAll('[data-synth-confirm-ok]').forEach(function (btn) {
        btn.addEventListener('click', onConfirmClick);
    });
    root.querySelectorAll('[data-synth-confirm-cancel], [data-synth-confirm-backdrop]').forEach(function (el) {
        el.addEventListener('click', close);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !root.classList.contains('hidden')) {
            close();
        }
    });

    window.SynthConfirm = {
        open: function (opts) {
            opts = opts || {};
            titleEl.textContent = opts.title || 'Xác nhận thao tác';
            msgEl.textContent = opts.message || '';
            pendingForm = null;
            pendingCallback = typeof opts.onConfirm === 'function' ? opts.onConfirm : null;
            root.classList.remove('hidden');
            root.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        },
        close: close
    };
})();
</script>

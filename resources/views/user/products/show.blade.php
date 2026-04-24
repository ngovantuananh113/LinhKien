@extends('layouts.shop-synth')

@section('title', $product->name)

@push('head')
<style>
    .chamfer-tr-bl {
        clip-path: polygon(0% 0%, 90% 0%, 100% 10%, 100% 100%, 10% 100%, 0% 90%);
    }
    .circuit-bg {
        background-image: radial-gradient(circle at 2px 2px, rgba(123, 47, 247, 0.05) 1px, transparent 0);
        background-size: 24px 24px;
    }
    .product-detail-tab-panel[hidden] { display: none !important; }
</style>
@endpush

@section('content')
@php
    $cat = $product->category;
    $catName = $cat?->name ?? '';
    $chipMap = [
        'CPU' => ['AM5_READY', 'BOOST_5G+'],
        'VGA' => ['RTX_READY', 'OC_S2'],
        'RAM' => ['XMP_3.0', 'RGB_SYNC'],
        'SSD' => ['NVME_GEN4', 'ĐỌC_7GB_S'],
        'Mainboard' => ['PCIe_5.0', 'DDR5_READY'],
        'Nguồn' => ['80_PLUS_GOLD', 'MODULAR'],
        'Tản nhiệt' => ['PWM_4PIN', 'ARGB'],
        'Case' => ['AIRFLOW+', 'TEMP_GLASS'],
        'Chuột' => ['8000_DPI', 'WIRELESS_2G'],
        'Bàn phím' => ['HOT_SWAP', 'RGB_PER_KEY'],
    ];
    $chips = $chipMap[$catName] ?? ['CHÍNH_HÃNG', 'BẢO_HÀNH_36T'];
    $specMap = [
        'CPU' => ['label' => 'Kiến trúc', 'value' => 'Zen 4 / Raptor Lake', 'label2' => 'Socket', 'value2' => 'AM5 / LGA1700'],
        'VGA' => ['label' => 'Kiến trúc', 'value' => 'Ada Lovelace / RDNA 3', 'label2' => 'VRAM', 'value2' => '8–24 GB GDDR6(X)'],
        'RAM' => ['label' => 'Chuẩn', 'value' => 'DDR4 / DDR5', 'label2' => 'Tốc độ', 'value2' => '3200–6000 MHz'],
        'SSD' => ['label' => 'Giao tiếp', 'value' => 'NVMe PCIe 4.0', 'label2' => 'Dung lượng', 'value2' => '500 GB – 2 TB'],
        'Mainboard' => ['label' => 'Chipset', 'value' => 'B650 / Z790', 'label2' => 'Form factor', 'value2' => 'ATX / mATX'],
        'Nguồn' => ['label' => 'Công suất', 'value' => '650–1000W', 'label2' => 'Hiệu suất', 'value2' => '80 Plus Gold'],
        'Tản nhiệt' => ['label' => 'Loại', 'value' => 'Tản khí / AIO', 'label2' => 'TDP hỗ trợ', 'value2' => '≤ 250W'],
        'Case' => ['label' => 'Form factor', 'value' => 'Mid Tower', 'label2' => 'Mainboard', 'value2' => 'ATX / mATX / ITX'],
        'Chuột' => ['label' => 'Cảm biến', 'value' => 'Quang học', 'label2' => 'Độ phân giải', 'value2' => '8000 DPI'],
        'Bàn phím' => ['label' => 'Switch', 'value' => 'Cơ học', 'label2' => 'Kết nối', 'value2' => 'USB-C / 2.4G'],
    ];
    $spec = $specMap[$catName] ?? ['label' => 'Danh mục', 'value' => $catName !== '' ? $catName : '—', 'label2' => 'Tồn kho', 'value2' => (string) $product->quantity];
    $nameParts = preg_split('/\s+/u', trim($product->name), -1, PREG_SPLIT_NO_EMPTY);
    $titleLast = count($nameParts) > 1 ? array_pop($nameParts) : null;
    $titleFirst = $titleLast !== null ? implode(' ', $nameParts) : null;
    $badgeClass = 'text-xs bg-primary-container/20 text-primary px-2 py-1 border border-primary/20';
    if ($product->quantity <= 0) {
        $badgeText = 'HẾT HÀNG';
        $badgeClass = 'text-xs bg-red-900/40 text-red-200 px-2 py-1 border border-red-500/30';
    } elseif ($product->quantity < 5) {
        $badgeText = 'SẮP HẾT';
    } else {
        $badgeText = 'CÒN HÀNG';
    }
    $desc = $product->description ?? '';
    $tabPerf = \Illuminate\Support\Str::limit(strip_tags($desc), 600);
    $tabArch = trim(($cat?->name ? 'Danh mục: '.$cat->name.'. ' : '').'Mã kho: #'.$product->id.'. Tồn: '.$product->quantity.' đơn vị.');
    $tabThermal = 'Thiết kế tản nhiệt và nguồn điện được tối ưu cho ổn định lâu dài. Khuyến nghị: bố trí luồng khí hợp lý, vệ sinh định kỳ để duy trì hiệu năng.';
@endphp

<div class="circuit-bg max-w-7xl mx-auto px-4 sm:px-8 w-full pb-20">
    <p class="mb-8">
        <a href="{{ route('products.index') }}" class="text-secondary-container font-headline text-xs uppercase tracking-widest hover:text-primary inline-flex items-center gap-1">
            <span class="material-symbols-outlined text-base">arrow_back</span> Sản phẩm
        </a>
    </p>

    <section class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">
        <div class="lg:col-span-7 relative group">
            <div class="absolute -inset-4 bg-primary-container/10 blur-3xl opacity-50 pointer-events-none" aria-hidden="true"></div>
            <div class="relative bg-surface-container-low p-6 sm:p-8 chamfer-tr-bl border border-outline-variant/20 overflow-hidden">
                @if($product->imageUrl())
                    <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="w-full h-auto max-h-[min(520px,60vh)] object-contain mx-auto transform transition-transform duration-500 group-hover:scale-[1.02] bg-black/40">
                @else
                    <div class="aspect-square flex items-center justify-center text-on-surface-variant min-h-[280px] bg-surface-container-lowest">
                        <span class="material-symbols-outlined text-8xl opacity-20">memory</span>
                    </div>
                @endif
                <div class="absolute top-6 left-6 flex flex-col gap-2 pointer-events-none">
                    <span class="bg-surface-container-highest px-3 py-1 text-[10px] font-headline uppercase tracking-widest text-secondary-fixed border-l-2 border-secondary-container">{{ $chips[0] }}</span>
                    <span class="bg-surface-container-highest px-3 py-1 text-[10px] font-headline uppercase tracking-widest text-primary border-l-2 border-primary-container">{{ $chips[1] }}</span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-5 flex flex-col gap-8 bg-surface-container/40 backdrop-blur-md p-6 sm:p-10 border border-outline-variant/10 chamfer-tr-bl">
            <div>
                <span class="text-secondary-container text-xs font-headline tracking-[0.3em] uppercase mb-2 block">{{ strtoupper($cat?->description ?? $cat?->name ?? 'DÒNG SẢN PHẨM') }}</span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-headline font-bold text-on-surface leading-none tracking-tighter uppercase mb-4">
                    @if($titleFirst !== null)
                        {{ $titleFirst }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-tertiary-container to-secondary-container">{{ $titleLast }}</span>
                    @else
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-tertiary-container to-secondary-container">{{ $product->name }}</span>
                    @endif
                </h1>
                <div class="flex flex-wrap items-center gap-4">
                    <span class="text-2xl sm:text-3xl font-headline font-light text-secondary-container">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                    <span class="{{ $badgeClass }} font-headline uppercase tracking-wider">{{ $badgeText }}</span>
                </div>
            </div>
            <div class="h-px bg-gradient-to-r from-outline-variant/50 to-transparent"></div>
            @if($desc !== '')
                <p class="text-on-surface-variant font-body text-sm leading-relaxed whitespace-pre-wrap">{{ $desc }}</p>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-surface-container-low p-4 border-l-2 border-outline-variant">
                    <span class="block text-[10px] text-outline uppercase tracking-widest font-headline">{{ $spec['label'] }}</span>
                    <span class="text-sm font-bold text-on-surface uppercase mt-1 block">{{ $spec['value'] }}</span>
                </div>
                <div class="bg-surface-container-low p-4 border-l-2 border-outline-variant">
                    <span class="block text-[10px] text-outline uppercase tracking-widest font-headline">{{ $spec['label2'] }}</span>
                    <span class="text-sm font-bold text-on-surface uppercase mt-1 block">{{ $spec['value2'] }}</span>
                </div>
            </div>

            <div class="flex flex-col gap-6 mt-2">
                @auth
                    @if($product->quantity > 0)
                        <form id="product-add-cart-form" action="{{ route('cart.items.store') }}" method="post" class="space-y-6">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="flex flex-wrap items-center gap-4">
                                <label for="qty-input" class="text-[10px] text-outline uppercase tracking-widest font-headline">Số lượng</label>
                                <div class="flex items-center bg-surface-container-highest border border-outline-variant/30">
                                    <button type="button" class="product-qty-minus p-2 hover:bg-surface-container-high text-primary transition-colors material-symbols-outlined select-none" aria-label="Giảm">remove</button>
                                    <input type="number" name="quantity" id="qty-input" value="1" min="1" max="{{ $product->quantity }}"
                                        class="w-14 text-center bg-transparent border-none text-on-surface font-headline text-sm focus:ring-0 [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
                                    <button type="button" class="product-qty-plus p-2 hover:bg-surface-container-high text-primary transition-colors material-symbols-outlined select-none" aria-label="Tăng">add</button>
                                </div>
                            </div>
                            <button type="submit" class="w-full py-5 bg-gradient-to-r from-primary-container to-secondary-container text-on-primary font-headline font-bold uppercase tracking-widest transition-all hover:brightness-110 active:scale-[0.98] shadow-[0_0_20px_rgba(123,47,247,0.4)]">
                                Thêm vào giỏ hàng
                            </button>
                        </form>
                    @else
                        <p class="px-4 py-3 border border-red-400/30 text-red-300 text-sm font-body">Sản phẩm đang hết hàng.</p>
                    @endif
                @else
                    <p class="text-on-surface-variant text-sm font-body"><a href="{{ route('login') }}" class="text-secondary-container hover:underline">Đăng nhập</a> để thêm vào giỏ và đặt hàng.</p>
                @endauth
                <a href="#tabs-product-detail" class="block w-full py-4 text-center bg-transparent border border-primary/40 text-primary font-headline font-medium uppercase tracking-widest hover:bg-surface-container-highest transition-all">
                    Bản thuyết minh kỹ thuật
                </a>
            </div>
        </div>
    </section>

    <div id="tabs-product-detail" class="mt-20 lg:mt-28 mb-12 scroll-mt-28">
        <div class="flex flex-wrap gap-6 sm:gap-10 border-b border-outline-variant/20 mb-6" role="tablist" aria-label="Chi tiết sản phẩm">
            <button type="button" role="tab" aria-selected="true" aria-controls="panel-perf" id="tab-perf" data-product-tab="perf"
                class="pb-4 border-b-2 border-secondary-container text-secondary-container font-headline uppercase text-xs sm:text-sm tracking-widest">
                Hiệu năng tham chiếu
            </button>
            <button type="button" role="tab" aria-selected="false" aria-controls="panel-arch" id="tab-arch" data-product-tab="arch"
                class="pb-4 border-b-2 border-transparent text-outline hover:text-on-surface transition-colors font-headline uppercase text-xs sm:text-sm tracking-widest">
                Chi tiết kiến trúc
            </button>
            <button type="button" role="tab" aria-selected="false" aria-controls="panel-thermal" id="tab-thermal" data-product-tab="thermal"
                class="pb-4 border-b-2 border-transparent text-outline hover:text-on-surface transition-colors font-headline uppercase text-xs sm:text-sm tracking-widest">
                Tản nhiệt &amp; điện
            </button>
        </div>
        <div id="panel-perf" role="tabpanel" aria-labelledby="tab-perf" class="product-detail-tab-panel text-sm text-on-surface-variant font-body leading-relaxed max-w-4xl">
            {{ $tabPerf !== '' ? $tabPerf : 'Nội dung hiệu năng sẽ được cập nhật theo bài test thực tế và cấu hình máy chủ.' }}
        </div>
        <div id="panel-arch" role="tabpanel" aria-labelledby="tab-arch" class="product-detail-tab-panel text-sm text-on-surface-variant font-body leading-relaxed max-w-4xl" hidden>
            {{ $tabArch }}
        </div>
        <div id="panel-thermal" role="tabpanel" aria-labelledby="tab-thermal" class="product-detail-tab-panel text-sm text-on-surface-variant font-body leading-relaxed max-w-4xl" hidden>
            {{ $tabThermal }}
        </div>
    </div>

    @if($relatedProducts->isNotEmpty())
        <section aria-labelledby="related-heading">
            <h2 id="related-heading" class="text-xl sm:text-2xl font-headline font-bold uppercase tracking-tighter text-on-surface mb-8 flex items-center gap-4">
                <span class="w-8 h-0.5 bg-secondary-container shrink-0" aria-hidden="true"></span>
                Linh kiện bổ trợ
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $rp)
                    <article class="group relative bg-surface-container-low border border-outline-variant/20 hover:border-secondary-container/50 transition-all duration-300 flex flex-col">
                        <a href="{{ route('products.show', $rp) }}" class="block h-48 overflow-hidden bg-surface-container-lowest">
                            @if($rp->imageUrl())
                                <img src="{{ $rp->imageUrl() }}" alt="{{ $rp->name }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500 scale-105 group-hover:scale-100">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-on-surface-variant/30"><span class="material-symbols-outlined text-5xl">devices</span></div>
                            @endif
                        </a>
                        <div class="p-5 flex flex-col gap-4 flex-1">
                            <div>
                                <span class="text-[10px] text-primary tracking-widest uppercase font-headline">{{ strtoupper($rp->category?->name ?? 'Sản phẩm') }}</span>
                                <h3 class="text-on-surface font-headline font-bold uppercase tracking-tight text-lg mt-1">
                                    <a href="{{ route('products.show', $rp) }}" class="hover:text-secondary-container transition-colors">{{ $rp->name }}</a>
                                </h3>
                            </div>
                            <div class="flex justify-between items-center mt-auto">
                                <span class="text-secondary-container font-headline">{{ number_format($rp->price, 0, ',', '.') }} đ</span>
                                @auth
                                    @if($rp->quantity > 0)
                                        <form action="{{ route('cart.items.store') }}" method="post" class="inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $rp->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="material-symbols-outlined text-outline group-hover:text-secondary-container transition-colors p-1" title="Thêm nhanh">add_shopping_cart</button>
                                        </form>
                                    @else
                                        <span class="text-[10px] text-outline uppercase">Hết hàng</span>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="material-symbols-outlined text-outline hover:text-secondary-container transition-colors p-1" title="Đăng nhập để mua">add_shopping_cart</a>
                                @endauth
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    var maxQty = {{ (int) $product->quantity }};
    var form = document.getElementById('product-add-cart-form');
    var input = document.getElementById('qty-input');
    if (form && input && maxQty > 0) {
        var minus = form.querySelector('.product-qty-minus');
        var plus = form.querySelector('.product-qty-plus');
        function clamp() {
            var v = parseInt(input.value, 10);
            if (isNaN(v) || v < 1) v = 1;
            if (v > maxQty) v = maxQty;
            input.value = v;
        }
        minus && minus.addEventListener('click', function () {
            input.value = Math.max(1, (parseInt(input.value, 10) || 1) - 1);
            clamp();
        });
        plus && plus.addEventListener('click', function () {
            input.value = Math.min(maxQty, (parseInt(input.value, 10) || 1) + 1);
            clamp();
        });
        input.addEventListener('change', clamp);
    }

    var tabs = document.querySelectorAll('[data-product-tab]');
    var panels = {
        perf: document.getElementById('panel-perf'),
        arch: document.getElementById('panel-arch'),
        thermal: document.getElementById('panel-thermal')
    };
    function activate(key) {
        Object.keys(panels).forEach(function (k) {
            var p = panels[k];
            if (!p) return;
            p.hidden = k !== key;
        });
        tabs.forEach(function (btn) {
            var k = btn.getAttribute('data-product-tab');
            var sel = k === key;
            btn.setAttribute('aria-selected', sel ? 'true' : 'false');
            btn.classList.toggle('border-secondary-container', sel);
            btn.classList.toggle('text-secondary-container', sel);
            btn.classList.toggle('border-transparent', !sel);
            btn.classList.toggle('text-outline', !sel);
        });
    }
    tabs.forEach(function (btn) {
        btn.addEventListener('click', function () {
            activate(btn.getAttribute('data-product-tab'));
        });
    });
})();
</script>
@endpush

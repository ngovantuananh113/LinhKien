@php
    $priceMax = (int) request('price_max', 50000000);
    $priceMax = min(max($priceMax, 0), 50000000);
    $selectedCats = array_filter(array_map('intval', (array) request('categories', request('category') ? [request('category')] : [])));
    $selectedArch = array_values(array_intersect(['NVIDIA', 'AMD', 'INTEL', 'ARM'], (array) request('arch', [])));
    $filterLabel = 'tất cả linh kiện';
    if (count($selectedCats) === 1) {
        $c = $categories->firstWhere('id', $selectedCats[0]);
        $filterLabel = $c ? $c->name : $filterLabel;
    } elseif (count($selectedCats) > 1) {
        $filterLabel = 'đã chọn lọc';
    }
    $sortLabels = [
        '' => 'Mới nhất',
        'price_asc' => 'Giá tăng dần',
        'price_desc' => 'Giá giảm dần',
        'name' => 'Tên A–Z',
    ];
    $currentSort = request('sort', '');
    $sortLabel = $sortLabels[$currentSort] ?? 'Mới nhất';
@endphp

<div class="flex flex-col lg:flex-row lg:items-start w-full min-h-[50vh]">
    {{-- Sidebar: cao ~full viewport, sticky, cuộn nội bộ + scrollbar tùy chỉnh --}}
    <form id="catalog-filter" method="get" action="{{ route('products.index') }}"
        class="w-full lg:w-[min(100%,18rem)] xl:w-72 shrink-0 flex flex-col max-lg:relative lg:sticky lg:top-24 lg:z-30 lg:h-[calc(100vh-6rem)] lg:max-h-[calc(100vh-6rem)] lg:self-start">
        {{-- Toàn bộ cột lọc cố định chiều cao viewport; chỉ khối danh mục có thanh kéo --}}
        <aside class="catalog-filter-aside flex flex-col flex-1 min-h-0 h-full overflow-hidden p-4 sm:p-5 bg-surface-container-low border-b lg:border-b-0 lg:border-r border-outline-variant/20">
            <div class="flex items-center gap-2 mb-4 shrink-0">
                <span class="material-symbols-outlined text-secondary-container text-xl">tune</span>
                <h2 class="font-headline text-base uppercase tracking-widest text-primary">Bộ lọc</h2>
            </div>

            <h3 class="font-label text-[10px] uppercase tracking-[0.2em] text-outline mb-2 shrink-0">Danh mục</h3>
            <div class="catalog-category-scroll flex-1 min-h-0 overflow-y-auto synth-scrollbar mb-4 rounded-sm border border-outline-variant/20 bg-surface-container-lowest/40 px-2 py-2">
                <div class="space-y-2">
                    @foreach($categories as $cat)
                        <label class="flex items-center group cursor-pointer py-0.5">
                            <input type="checkbox" name="categories[]" value="{{ $cat->id }}" class="hidden peer" @checked(in_array($cat->id, $selectedCats, true))>
                            <div class="w-3.5 h-3.5 border border-outline-variant peer-checked:bg-secondary-container peer-checked:border-secondary-container transition-all shrink-0"></div>
                            <span class="ml-2.5 text-xs font-body uppercase tracking-tight group-hover:text-secondary-container peer-checked:text-secondary-container transition-colors">{{ $cat->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4 shrink-0">
                <h3 class="font-label text-[10px] uppercase tracking-[0.2em] text-outline mb-2">Ngân sách tối đa</h3>
                <p class="text-secondary-container font-headline text-sm mb-2 tabular-nums" id="catalog-price-label">{{ number_format($priceMax, 0, ',', '.') }} đ</p>
                <div class="px-0.5">
                    <input type="range" name="price_max" min="0" max="50000000" step="100000" value="{{ $priceMax }}"
                        id="catalog-price-range"
                        class="catalog-range w-full h-1 bg-surface-container-highest rounded-none appearance-none cursor-pointer accent-secondary-container">
                    <div class="flex justify-between mt-2 font-headline text-[10px] text-outline">
                        <span>0</span>
                        <span>50tr</span>
                    </div>
                </div>
            </div>

            <div class="mb-4 shrink-0">
                <h3 class="font-label text-[10px] uppercase tracking-[0.2em] text-outline mb-2">Kiến trúc / hãng</h3>
                <div class="grid grid-cols-2 gap-1.5">
                    @foreach (['NVIDIA' => 'NVIDIA', 'AMD' => 'AMD', 'INTEL' => 'INTEL', 'ARM' => 'ARM'] as $val => $lab)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="arch[]" value="{{ $val }}" class="peer sr-only" @checked(in_array($val, $selectedArch, true))>
                            <span class="block py-1.5 px-1 text-center bg-surface-container-highest text-[9px] font-headline uppercase tracking-widest border border-outline-variant/30 peer-checked:border-secondary-container peer-checked:text-secondary-container peer-checked:shadow-[0_0_8px_rgba(0,244,254,0.15)] hover:border-secondary-container/50 transition-all">{{ $lab }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="space-y-2 shrink-0">
                <label class="font-label text-[10px] uppercase tracking-widest text-outline">Tìm kiếm</label>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Tên, mô tả…"
                    autocomplete="off"
                    class="w-full bg-surface-container-lowest border border-outline-variant/30 px-3 py-2 text-sm text-on-surface focus:border-secondary-container focus:outline-none focus:ring-1 focus:ring-secondary-container/30 placeholder:text-on-surface-variant/45">
            </div>

            <input type="hidden" name="sort" id="catalog-sort-input" value="{{ $currentSort }}">

            <div class="mt-4 pt-4 shrink-0 flex flex-col gap-2 border-t border-outline-variant/15">
                <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-primary-container to-secondary-container text-white font-headline text-[10px] font-bold uppercase tracking-widest shadow-[0_0_14px_rgba(0,244,254,0.22)] hover:brightness-110 active:scale-[0.99] transition-all">
                    Áp dụng lọc
                </button>
                <button type="button" id="catalog-clear-btn" class="w-full py-2.5 border border-outline-variant/50 text-on-surface-variant text-[10px] font-headline uppercase tracking-widest hover:border-secondary-container/60 hover:text-secondary-container transition-all">
                    Xóa lọc
                </button>
            </div>
        </aside>
    </form>

    <section class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-background min-w-0">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6 md:mb-8">
            <div class="min-w-0">
                <h1 class="font-headline text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold uppercase tracking-tighter text-on-surface leading-tight">Linh kiện phần cứng</h1>
                <p class="font-body text-outline mt-2 tracking-widest uppercase text-[10px] sm:text-xs" id="catalog-result-line">
                    Đang xem <span class="text-on-surface tabular-nums">{{ $products->total() }}</span> sản phẩm
                    @if($filterLabel !== 'tất cả linh kiện')
                        — <span class="text-secondary-container">{{ $filterLabel }}</span>
                    @else
                        — <span class="text-secondary-container">toàn bộ danh mục</span>
                    @endif
                </p>
            </div>

            <div class="relative shrink-0 w-full sm:w-auto sm:min-w-[240px]" data-catalog-sort-wrap>
                <button type="button" id="catalog-sort-toggle" aria-expanded="false" aria-haspopup="listbox"
                    class="catalog-sort-trigger flex w-full items-center justify-between gap-3 px-4 py-3 bg-[#0e0e0e] border border-primary/35 text-left shadow-[inset_0_0_0_1px_rgba(123,47,247,0.15)] hover:border-secondary-container/50 hover:shadow-[0_0_20px_rgba(0,244,254,0.12)] transition-all duration-200">
                    <span class="flex flex-col gap-0.5 min-w-0">
                        <span class="text-[9px] font-headline uppercase tracking-[0.25em] text-outline">Sắp xếp</span>
                        <span class="font-headline text-xs uppercase tracking-wide text-secondary-container truncate catalog-sort-current">{{ $sortLabel }}</span>
                    </span>
                    <span class="material-symbols-outlined text-secondary-container text-xl shrink-0 catalog-sort-chevron transition-transform duration-200">expand_more</span>
                </button>
                <div id="catalog-sort-menu" role="listbox" class="catalog-sort-menu synth-scrollbar hidden absolute right-0 left-0 sm:left-auto sm:right-0 top-full z-40 mt-1.5 min-w-full w-full sm:w-[min(100%,280px)] max-h-[min(50vh,320px)] overflow-y-auto py-1 bg-[#0a0a0a] border border-secondary-container/25 shadow-[0_12px_40px_rgba(0,0,0,0.75)] origin-top transition-all duration-200 ease-out opacity-0 -translate-y-1 pointer-events-none">
                    @foreach($sortLabels as $val => $label)
                        <button type="button" role="option" data-sort-value="{{ $val }}" data-sort-label="{{ $label }}"
                            class="catalog-sort-option w-full text-left px-4 py-3 text-xs font-headline uppercase tracking-wider border-l-2 transition-colors
                                {{ $currentSort === $val ? 'border-secondary-container bg-secondary-container/10 text-secondary-container' : 'border-transparent text-on-surface hover:bg-primary-container/15 hover:text-primary hover:border-primary/40' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div id="catalog-grid-wrap">
            @if($products->isEmpty())
                <p class="text-on-surface-variant border border-outline-variant/20 bg-surface-container-low p-8 text-center text-sm">
                    Không có sản phẩm phù hợp. Thử đổi bộ lọc hoặc <button type="button" class="text-secondary-container underline catalog-inline-clear">xóa lọc</button>.
                </p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                    @foreach($products as $product)
                        @php
                            $catName = $product->category?->name ?? 'Linh kiện';
                            $feature = match (true) {
                                str_contains(strtoupper($catName), 'CPU') => 'ĐA NHÂN',
                                str_contains(strtoupper($catName), 'VGA') => 'SẴN SÀNG RTX',
                                str_contains(strtoupper($catName), 'RAM') => 'TỐC ĐỘ CAO',
                                default => 'CHẤT LƯỢNG',
                            };
                            $line1 = strtoupper($catName).' // MODULE';
                            $limited = $product->quantity > 0 && $product->quantity < 5;
                        @endphp
                        <article class="catalog-card-enter group relative flex flex-col bg-surface-container-lowest border border-outline-variant/10 hover:border-secondary-container/50 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_40px_rgba(0,0,0,0.35)]" style="animation-delay: {{ min($loop->index * 42, 480) }}ms">
                            <div class="absolute -top-1 -left-1 w-4 h-4 border-t border-l border-secondary-container opacity-0 group-hover:opacity-100 transition-all pointer-events-none"></div>
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 border-b border-r border-secondary-container opacity-0 group-hover:opacity-100 transition-all pointer-events-none"></div>

                            <div class="relative overflow-hidden aspect-square bg-surface-container">
                                <a href="{{ route('products.show', $product) }}" class="block w-full h-full">
                                    @if($product->imageUrl())
                                        <img src="{{ $product->imageUrl() }}" alt="" class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition-all duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-surface-container-highest"><span class="material-symbols-outlined text-6xl text-outline-variant/30">inventory_2</span></div>
                                    @endif
                                </a>
                                @if($product->quantity > 0)
                                    <div class="absolute top-3 right-3 {{ $limited ? 'bg-tertiary-container text-white' : 'bg-primary-container text-white' }} text-[10px] font-headline uppercase tracking-widest px-3 py-1">
                                        {{ $limited ? 'Sắp hết' : 'Còn hàng' }}
                                    </div>
                                @else
                                    <div class="absolute top-3 right-3 bg-surface-container-highest text-on-surface-variant text-[10px] font-headline uppercase tracking-widest px-3 py-1">Hết hàng</div>
                                @endif
                            </div>

                            <div class="p-5 sm:p-6 flex flex-col flex-1">
                                <div class="flex justify-between items-start gap-2 mb-2">
                                    <span class="text-[10px] font-label uppercase tracking-widest text-outline line-clamp-1">{{ $line1 }}</span>
                                    <span class="text-[10px] font-headline text-secondary-container shrink-0">{{ $feature }}</span>
                                </div>
                                <h3 class="font-headline text-lg font-bold uppercase tracking-tight text-on-surface mb-1 group-hover:text-primary transition-colors line-clamp-2">
                                    <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                                </h3>
                                <p class="text-xs text-outline font-body leading-relaxed mb-5 line-clamp-2">{{ $product->description ? \Illuminate\Support\Str::limit(strip_tags($product->description), 90) : 'Linh kiện chính hàng, bảo hành theo nhà sản xuất.' }}</p>
                                <div class="mt-auto flex flex-col gap-3">
                                    <div class="text-2xl font-headline font-bold text-secondary-fixed tracking-tighter">{{ number_format($product->price, 0, ',', '.') }} đ</div>
                                    @auth
                                        @if($product->quantity > 0)
                                            <form action="{{ route('cart.items.store') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="w-full py-3 sm:py-4 bg-gradient-to-r from-primary-container to-secondary-container text-white font-headline font-bold uppercase tracking-widest text-xs sm:text-sm shadow-[0_0_15px_rgba(0,244,254,0.3)] hover:shadow-[0_0_25px_rgba(0,244,254,0.55)] active:scale-[0.98] transition-all">
                                                    Thêm vào giỏ
                                                </button>
                                            </form>
                                        @else
                                            <span class="w-full py-3 text-center border border-outline-variant/40 text-on-surface-variant text-xs uppercase tracking-widest">Hết hàng</span>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="block w-full py-3 sm:py-4 text-center bg-gradient-to-r from-primary-container/80 to-secondary-container/80 text-white font-headline font-bold uppercase tracking-widest text-xs sm:text-sm">Đăng nhập để mua</a>
                                    @endauth
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-10 sm:mt-14 flex flex-col items-center gap-5 w-full max-w-4xl mx-auto catalog-pagination-enter">
                    <div class="circuit-line-catalog circuit-line-catalog-anim max-w-md w-full"></div>
                    {{ $products->withQueryString()->links('vendor.pagination.synth') }}
                </div>
            @endif
        </div>
    </section>
</div>

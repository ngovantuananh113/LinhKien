@extends('layouts.shop-synth')

@section('title', 'Trang chủ')

@php
    $bento = $featured->take(5);
    $pLarge = $bento->get(0);
    $pMedium = $bento->get(1);
    $pSmall = $bento->slice(2, 3);
    $catIcons = ['memory', 'videogame_asset', 'database', 'settings_input_component', 'ac_unit', 'power'];
    $heroImg = 'https://lh3.googleusercontent.com/aida-public/AB6AXuAJ6GIX3cI1BMpINbf2cEfsVVIf0X9xUmO61gKU4rKQBB0FeffdS7K24fbD2EqOa6CbgVDiU6oSOiZvEBTYzJ7eswUBUjS5MTox2fQO4So5ZGxnJLIApUV-6TkMobuFikM28GyVMJpr-QIWkq23u_7fGmo5qlFIeXKgOA9U5_YAodXQ6RUkWbLcRy6dY8I4eHBRjKFk8xeem35uWw9h1QMB1Mj8z_uZ98gxkDCCjGHEL27tscg-C3aZd2Ts2OCPFGWVS6bb_QngkrA';
@endphp

@section('content')
{{-- Hero — homepage_build_your_ultimate_pc --}}
<section class="relative min-h-[min(870px,92vh)] flex items-center overflow-hidden px-6 lg:px-16">
    <div class="absolute inset-0 z-0 bg-surface-container-lowest">
        <div class="absolute top-0 right-0 w-1/2 h-full opacity-40 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-primary-container/20 via-transparent to-transparent"></div>
        <img src="{{ $heroImg }}" alt="" class="w-full h-full object-cover mix-blend-lighten opacity-60" loading="eager">
    </div>
    <div class="relative z-10 max-w-4xl">
        <div class="inline-block bg-surface-container-highest px-4 py-1 mb-6 text-secondary-fixed font-label text-xs tracking-widest uppercase">
            Kiến trúc // Giai đoạn 01
        </div>
        <h1 class="font-headline text-4xl sm:text-6xl md:text-7xl lg:text-8xl font-bold leading-none tracking-tighter text-on-surface mb-6 sm:mb-8">
            DỰNG CẤU HÌNH<br/>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-container via-secondary-container to-secondary-fixed">PC TỐI THƯỢNG</span>
        </h1>
        <p class="font-body text-on-surface-variant text-base sm:text-lg md:text-xl max-w-xl mb-8 sm:mb-12">
            Thiết kế cho người chơi hệ thống: linh kiện chọn lọc, hiệu năng ổn định — trải nghiệm sức mạnh máy tính thế hệ tiếp theo.
        </p>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-primary-container to-secondary-container border border-secondary-fixed text-on-primary font-label px-6 sm:px-8 py-3 sm:py-4 uppercase tracking-widest text-xs sm:text-sm font-bold shadow-[0_0_15px_rgba(0,244,254,0.4)] transition-all hover:scale-[1.02] active:scale-95">
                Bắt đầu chọn linh kiện
            </a>
            <a href="#featured-hardware" class="inline-flex items-center justify-center border border-primary text-primary font-label px-6 sm:px-8 py-3 sm:py-4 uppercase tracking-widest text-xs sm:text-sm font-bold hover:bg-surface-container-highest transition-all active:scale-95">
                Xem linh kiện nổi bật
            </a>
        </div>
    </div>
    <div class="absolute bottom-8 right-6 lg:right-12 hidden lg:flex flex-col gap-3 text-right opacity-30 font-label text-[10px] tracking-[0.35em] text-secondary-fixed">
        <div>NHIỆT ĐỘ: 32°C</div>
        <div>TẢI HỆ THỐNG: TỐI ƯU</div>
    </div>
</section>

{{-- Danh mục nhanh --}}
<section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-[2px] bg-outline-variant/20 border-y border-outline-variant/20">
    @forelse($categories as $cat)
        <a href="{{ route('products.index', ['category' => $cat->id]) }}" class="group relative aspect-square bg-surface-container-low flex flex-col items-center justify-center transition-all hover:bg-surface-container-high overflow-hidden p-2 text-center">
            <span class="material-symbols-outlined text-3xl sm:text-4xl mb-2 sm:mb-4 {{ $loop->iteration % 2 ? 'text-primary' : 'text-secondary-container' }} group-hover:scale-110 transition-transform">{{ $catIcons[$loop->index] ?? 'category' }}</span>
            <span class="font-label text-[10px] sm:text-xs tracking-widest uppercase leading-tight px-1">{{ $cat->name }}</span>
            <div class="absolute bottom-0 left-0 w-0 h-1 bg-secondary-container transition-all group-hover:w-full"></div>
        </a>
    @empty
        <div class="col-span-full py-12 text-center text-on-surface-variant text-sm">Chưa có danh mục. Vui lòng thêm trong quản trị.</div>
    @endforelse
</section>

{{-- Featured — bento grid + dữ liệu thật --}}
<section id="featured-hardware" class="py-16 sm:py-24 px-6 lg:px-16 bg-surface-container-lowest">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-10 sm:mb-16">
        <div>
            <h2 class="font-headline text-2xl sm:text-4xl font-bold uppercase tracking-tight mb-2 text-on-surface">Linh kiện nổi bật</h2>
            <div class="w-24 h-1 bg-primary-container"></div>
        </div>
        <a href="{{ route('products.index') }}" class="font-label text-xs text-secondary-container tracking-widest uppercase hover:underline shrink-0">Xem tất cả sản phẩm</a>
    </div>

    @if($featured->isEmpty())
        <p class="text-on-surface-variant border border-outline-variant/20 bg-surface-container-low p-8 text-center">Chưa có sản phẩm. Chạy <code class="text-primary">php artisan db:seed</code> hoặc thêm trong quản trị.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 sm:gap-8">
            @if($pLarge)
                <div class="md:col-span-7 lg:col-span-8 group relative bg-surface-container-low border border-outline-variant/20 transition-all hover:scale-[1.01] hover:border-secondary-container overflow-hidden min-h-[420px] sm:h-[500px]">
                    <div class="absolute top-0 right-0 p-4 sm:p-6 z-20">
                        <span class="bg-secondary-container text-on-secondary-container font-label text-[10px] font-bold px-3 py-1 uppercase tracking-widest">Mới</span>
                    </div>
                    <div class="absolute inset-0 z-0">
                        @if($pLarge->imageUrl())
                            <img src="{{ $pLarge->imageUrl() }}" alt="" class="w-full h-full object-cover opacity-50 group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-surface-container-highest flex items-center justify-center"><span class="material-symbols-outlined text-8xl text-primary/20">memory</span></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-surface-container-lowest via-transparent to-transparent"></div>
                    </div>
                    <div class="absolute bottom-0 left-0 p-6 sm:p-10 z-10 w-full">
                        <h3 class="font-headline text-2xl sm:text-4xl font-bold mb-3 sm:mb-4 uppercase leading-tight">{{ $pLarge->name }}</h3>
                        <div class="flex flex-wrap gap-2 mb-6 sm:mb-8">
                            @if($pLarge->category)
                                <span class="bg-surface-container-highest px-3 py-1 font-label text-[10px] tracking-widest uppercase">{{ $pLarge->category->name }}</span>
                            @endif
                            <span class="bg-surface-container-highest px-3 py-1 font-label text-[10px] tracking-widest uppercase">Còn {{ $pLarge->quantity }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <span class="text-2xl sm:text-3xl font-headline text-secondary-fixed">{{ number_format($pLarge->price, 0, ',', '.') }} đ</span>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('products.show', $pLarge) }}" class="border border-outline px-4 py-2 font-label text-[10px] uppercase tracking-widest text-on-surface hover:border-secondary-container hover:text-secondary-container transition-all">Chi tiết</a>
                                @auth
                                    @if($pLarge->quantity > 0)
                                        <form action="{{ route('cart.items.store') }}" method="post" class="inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $pLarge->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="bg-secondary-container text-on-secondary-container px-4 sm:px-6 py-2 sm:py-3 font-label text-xs font-bold uppercase tracking-widest hover:shadow-[0_0_15px_rgba(0,244,254,0.6)] transition-all">Thêm vào giỏ</button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="bg-secondary-container/80 text-on-secondary-container px-4 py-2 font-label text-xs font-bold uppercase tracking-widest">Đăng nhập để mua</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($pMedium)
                <div class="md:col-span-5 lg:col-span-4 group relative bg-surface-container-low border border-outline-variant/20 transition-all hover:scale-[1.02] hover:border-primary overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="aspect-square mb-6 overflow-hidden bg-surface-container-highest flex items-center justify-center p-4">
                            @if($pMedium->imageUrl())
                                <img src="{{ $pMedium->imageUrl() }}" alt="" class="w-full h-auto object-contain group-hover:scale-105 transition-transform duration-500">
                            @else
                                <span class="material-symbols-outlined text-7xl text-secondary-container/30">memory</span>
                            @endif
                        </div>
                        <div class="space-y-4">
                            <h3 class="font-headline text-xl sm:text-2xl font-bold uppercase">{{ $pMedium->name }}</h3>
                            <p class="font-body text-on-surface-variant text-sm line-clamp-2">{{ \Illuminate\Support\Str::limit($pMedium->description ?? 'Linh kiện chất lượng cao.', 120) }}</p>
                            <div class="flex items-center justify-between pt-4">
                                <span class="text-xl font-headline text-primary">{{ number_format($pMedium->price, 0, ',', '.') }} đ</span>
                                @auth
                                    @if($pMedium->quantity > 0)
                                        <form action="{{ route('cart.items.store') }}" method="post" class="inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $pMedium->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="material-symbols-outlined text-primary hover:text-secondary-fixed transition-colors p-2" title="Thêm giỏ">add_shopping_cart</button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="material-symbols-outlined text-primary hover:text-secondary-fixed p-2" title="Đăng nhập">add_shopping_cart</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @foreach($pSmall as $p)
                <div class="md:col-span-4 group relative bg-surface-container-low border border-outline-variant/20 transition-all hover:scale-[1.02] hover:border-secondary-container overflow-hidden">
                    <div class="p-6">
                        <a href="{{ route('products.show', $p) }}" class="block aspect-video mb-6 overflow-hidden bg-surface-container-highest">
                            @if($p->imageUrl())
                                <img src="{{ $p->imageUrl() }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            @else
                                <div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-5xl text-outline-variant/40">inventory_2</span></div>
                            @endif
                        </a>
                        <h3 class="font-headline text-lg font-bold uppercase mb-2 line-clamp-2">{{ $p->name }}</h3>
                        <p class="font-label text-xs text-on-surface-variant tracking-widest uppercase mb-4">{{ $p->category?->name ?? 'Linh kiện' }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-headline text-on-surface">{{ number_format($p->price, 0, ',', '.') }} đ</span>
                            <a href="{{ route('products.show', $p) }}" class="border border-outline px-4 py-2 font-label text-[10px] uppercase tracking-widest hover:border-secondary-container hover:text-secondary-container transition-all">Chi tiết</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>

{{-- Banner đăng ký nhận tin --}}
<section class="mx-6 lg:mx-16 mb-20 sm:mb-24 relative overflow-hidden chamfer-tl-br">
    <div class="bg-gradient-to-r from-primary-container to-secondary-container p-8 sm:p-12 lg:p-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <pattern id="grid-home" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"></path>
                </pattern>
                <rect width="100%" height="100%" fill="url(#grid-home)"></rect>
            </svg>
        </div>
        <div class="relative z-10 grid md:grid-cols-2 gap-10 lg:gap-12 items-center">
            <div>
                <h2 class="font-headline text-2xl sm:text-4xl lg:text-5xl font-bold text-on-primary mb-4 sm:mb-6 leading-tight uppercase tracking-tighter">TƯƠNG LAI LÀ TÙY CHỈNH.</h2>
                <p class="font-body text-on-primary-fixed-variant text-base sm:text-lg mb-8 max-w-lg">
                    Nhận thông tin ưu đãi và linh kiện mới — đăng ký email bên dưới (demo, không gửi mail thật).
                </p>
                <form action="{{ route('newsletter.subscribe') }}" method="post" class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    @csrf
                    <input name="email" type="email" required value="{{ old('email') }}" placeholder="email@example.com" class="bg-on-primary/10 border-b-2 border-on-primary text-on-primary placeholder:text-on-primary/50 focus:outline-none focus:border-white px-4 py-3 flex-grow font-label text-xs min-w-0">
                    <button type="submit" class="bg-on-primary text-primary-container font-label font-bold px-6 sm:px-8 py-3 uppercase tracking-widest text-xs hover:bg-white transition-colors shrink-0">Tham gia</button>
                </form>
                @error('email')
                    <p class="mt-2 text-sm text-on-primary">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-center md:justify-end">
                <div class="relative w-48 h-48 sm:w-64 sm:h-64 border-4 border-on-primary/30 rotate-45 flex items-center justify-center p-4">
                    <div class="w-full h-full border-2 border-on-primary/60 flex items-center justify-center">
                        <span class="material-symbols-outlined text-6xl sm:text-8xl text-on-primary -rotate-45">developer_board</span>
                    </div>
                    <div class="absolute -top-2 -left-2 w-4 h-4 bg-on-primary"></div>
                    <div class="absolute -bottom-2 -right-2 w-4 h-4 bg-on-primary"></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Dịch vụ --}}
<section class="py-12 sm:py-16 px-6 lg:px-16 border-t border-outline-variant/10">
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12">
        <div class="space-y-4">
            <span class="material-symbols-outlined text-primary text-4xl">verified_user</span>
            <h4 class="font-headline text-lg font-bold uppercase tracking-tight">Giao hàng bảo mật</h4>
            <p class="font-body text-on-surface-variant text-xs leading-relaxed">Đóng gói kỹ, theo dõi đơn — linh kiện đến tay đúng mô tả.</p>
        </div>
        <div class="space-y-4">
            <span class="material-symbols-outlined text-secondary-container text-4xl">precision_manufacturing</span>
            <h4 class="font-headline text-lg font-bold uppercase tracking-tight">Lắp ráp chính xác</h4>
            <p class="font-body text-on-surface-variant text-xs leading-relaxed">Tư vấn tương thích socket, nguồn và tản nhiệt cho cấu hình của bạn.</p>
        </div>
        <div class="space-y-4">
            <span class="material-symbols-outlined text-primary text-4xl">terminal</span>
            <h4 class="font-headline text-lg font-bold uppercase tracking-tight">Hỗ trợ kỹ thuật</h4>
            <p class="font-body text-on-surface-variant text-xs leading-relaxed">Giải đáp cài đặt driver, BIOS và xung đột phần cứng qua kênh hỗ trợ.</p>
        </div>
        <div class="space-y-4">
            <span class="material-symbols-outlined text-secondary-container text-4xl">recycling</span>
            <h4 class="font-headline text-lg font-bold uppercase tracking-tight">Thu hồi linh kiện cũ</h4>
            <p class="font-body text-on-surface-variant text-xs leading-relaxed">Chương trình thu máy cũ — tái sử dụng bền vững (theo chính sách cửa hàng).</p>
        </div>
    </div>
</section>
@endsection

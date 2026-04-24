@extends('layouts.shop-synth')

@section('title', 'Giỏ hàng')

@push('head')
<style>
    .cart-chamfer-card {
        clip-path: polygon(0 0, 100% 0, 100% calc(100% - 16px), calc(100% - 16px) 100%, 0 100%);
    }
    .checkout-chamfer {
        clip-path: polygon(0 0, 100% 0, 100% calc(100% - 16px), calc(100% - 16px) 100%, 0 100%);
    }
</style>
@endpush

@section('content')
@php
    $subtotal = $cart->items->sum(fn ($i) => $i->product->price * $i->quantity);
    $freight = 0;
    $tax = 0;
    $total = $subtotal + $freight + $tax;
    $fieldClass = 'w-full bg-transparent border-0 border-b-2 border-outline-variant focus:ring-0 focus:border-secondary-container text-secondary-fixed placeholder:text-outline-variant/70 font-headline text-base md:text-lg transition-all px-0 py-2 rounded-none';
@endphp
<div class="max-w-7xl mx-auto px-4 md:px-8 w-full pb-16 md:pb-20">
    <header class="mb-8 md:mb-12">
        <h1 class="font-headline text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-bold tracking-tighter uppercase text-primary drop-shadow-[0_0_15px_rgba(210,187,255,0.25)] leading-[1.05]">
            Terminal hệ thống <span class="text-secondary-container">/ Giỏ hàng</span>
        </h1>
        <div class="flex items-center gap-2 mt-4 text-outline uppercase tracking-[0.2em] text-[10px] font-headline font-bold">
            <span class="w-2 h-2 bg-secondary-container animate-pulse shrink-0" aria-hidden="true"></span>
            Giao thức bảo mật hoạt động
        </div>
        @if(!$cart->items->isEmpty())
            <p class="mt-4 text-sm text-on-surface-variant max-w-2xl">Nhập thông tin giao hàng bên dưới và xác nhận để đặt hàng — không cần chuyển trang.</p>
        @endif
    </header>

    @if($cart->items->isEmpty())
        <div class="border border-outline-variant/25 bg-surface-container-low p-10 md:p-12 text-center cart-chamfer-card">
            <span class="material-symbols-outlined text-5xl text-outline-variant/40 mb-4 block">shopping_cart</span>
            <p class="text-on-surface-variant font-body mb-6">Kho phần cứng trống — chưa có mô-đun nào được gán.</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-primary-container to-secondary-container text-on-primary font-headline text-xs font-bold uppercase tracking-widest hover:brightness-110 transition-all">
                Khám phá sản phẩm
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            {{-- Cột trái: danh sách --}}
            <div class="lg:col-span-8 space-y-10 md:space-y-12">
                <section aria-labelledby="cart-inventory-heading">
                    <div class="flex flex-wrap items-baseline justify-between gap-2 mb-6 border-b border-outline-variant/20 pb-2">
                        <h2 id="cart-inventory-heading" class="font-headline text-lg md:text-xl uppercase tracking-widest text-on-surface-variant">01. Kho_phần_cứng</h2>
                        <span class="font-headline text-[10px] md:text-xs text-outline uppercase tracking-widest">{{ $cart->items->count() }} mục trong giỏ</span>
                    </div>

                    <div class="space-y-4">
                        @foreach($cart->items as $item)
                            @php
                                $p = $item->product;
                                $line = $p->price * $item->quantity;
                                $catName = strtoupper($p->category?->name ?? 'Linh kiện');
                                $accent = $loop->odd ? 'border-primary-container' : 'border-secondary-container';
                                $tagClass = $loop->odd ? 'text-secondary-container' : 'text-primary';
                            @endphp
                            <article class="relative bg-surface-container-low group flex flex-col md:flex-row items-stretch md:items-center gap-4 md:gap-6 p-4 pt-10 md:pt-4 border-l-2 {{ $accent }} hover:bg-surface-container transition-colors cart-chamfer-card">
                                <form action="{{ route('cart.items.destroy', $item) }}" method="post" class="absolute top-2 right-2 z-10"
                                    data-synth-confirm
                                    data-confirm-title="Gỡ sản phẩm khỏi giỏ?"
                                    data-confirm-message="Mục sẽ bị xóa khỏi terminal giỏ hàng của bạn.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-outline hover:text-red-400 transition-colors" title="Gỡ khỏi giỏ">
                                        <span class="material-symbols-outlined text-lg">close</span>
                                    </button>
                                </form>
                                <div class="w-full md:w-28 lg:w-32 h-28 md:h-32 bg-surface-container-highest shrink-0 relative overflow-hidden mx-auto md:mx-0">
                                    @if($p->imageUrl())
                                        <img src="{{ $p->imageUrl() }}" alt="" class="w-full h-full object-cover mix-blend-lighten opacity-85 group-hover:opacity-100 transition-opacity">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-4xl text-outline-variant/25">inventory_2</span></div>
                                    @endif
                                </div>
                                <div class="flex-grow space-y-1 min-w-0 text-center sm:text-left">
                                    <span class="text-[10px] font-headline font-bold {{ $tagClass }} uppercase tracking-widest">{{ $catName }}</span>
                                    <h3 class="font-headline text-lg md:text-xl text-primary uppercase tracking-tight">
                                        <a href="{{ route('products.show', $p) }}" class="hover:text-secondary-container transition-colors">{{ $p->name }}</a>
                                    </h3>
                                    <p class="text-xs text-outline max-w-md mx-auto sm:mx-0 line-clamp-2">{{ $p->description ? \Illuminate\Support\Str::limit(strip_tags($p->description), 120) : 'Thiết bị chính hàng, đồng bộ với hệ thống SYNTH.' }}</p>
                                </div>
                                <div class="flex flex-row sm:flex-col md:flex-row items-center justify-center sm:justify-end gap-4 md:gap-6 shrink-0 w-full sm:w-auto">
                                    <div class="flex items-center border border-outline-variant/30">
                                        <form action="{{ route('cart.items.update', $item) }}" method="post" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="quantity" value="{{ max(1, $item->quantity - 1) }}">
                                            <button type="submit" class="p-2 hover:bg-primary-container text-primary hover:text-white transition-colors disabled:opacity-30 disabled:pointer-events-none" title="Giảm" {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                <span class="material-symbols-outlined text-sm">remove</span>
                                            </button>
                                        </form>
                                        <span class="px-3 md:px-4 font-headline text-sm tabular-nums text-on-surface min-w-[2.5rem] text-center">{{ str_pad((string) $item->quantity, 2, '0', STR_PAD_LEFT) }}</span>
                                        <form action="{{ route('cart.items.update', $item) }}" method="post" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="quantity" value="{{ min($p->quantity, $item->quantity + 1) }}">
                                            <button type="submit" class="p-2 hover:bg-secondary-container text-secondary-container hover:text-black transition-colors disabled:opacity-30 disabled:pointer-events-none" title="Tăng" {{ $item->quantity >= $p->quantity ? 'disabled' : '' }}>
                                                <span class="material-symbols-outlined text-sm">add</span>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="text-center sm:text-right min-w-[100px]">
                                        <div class="text-[10px] text-outline uppercase tracking-tighter font-headline">Tạm tính dòng</div>
                                        <div class="font-headline text-lg text-secondary-fixed font-bold tracking-tighter">{{ number_format($line, 0, ',', '.') }} đ</div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section aria-labelledby="cart-shipping-heading" class="mt-12 md:mt-16 pt-2 border-t border-outline-variant/10">
                    <div class="flex flex-wrap items-baseline justify-between gap-2 mb-6 md:mb-8 border-b border-outline-variant/20 pb-2">
                        <h2 id="cart-shipping-heading" class="font-headline text-lg md:text-xl uppercase tracking-widest text-on-surface-variant">02. Chỉ_dẫn_vận_hành</h2>
                        <span class="font-headline text-[10px] md:text-xs text-outline uppercase tracking-widest">Bắt buộc nhập</span>
                    </div>

                    <form id="checkout-form" action="{{ route('orders.store') }}" method="post" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-8 md:gap-y-10">
                        @csrf
                        <div class="space-y-2 group">
                            <label for="recipient_name" class="text-[10px] uppercase tracking-[0.3em] font-headline font-bold text-outline group-focus-within:text-secondary-container transition-colors">Họ_và_tên</label>
                            <input type="text" name="recipient_name" id="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}" required autocomplete="name"
                                class="{{ $fieldClass }}"
                                placeholder="KIẾN_TRÚC_SƯ_01">
                            @error('recipient_name')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2 group">
                            <label for="phone" class="text-[10px] uppercase tracking-[0.3em] font-headline font-bold text-outline group-focus-within:text-secondary-container transition-colors">ID_liên_lạc (Điện thoại)</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', auth()->user()->phone) }}" required autocomplete="tel"
                                class="{{ $fieldClass }}"
                                placeholder="+84 900 000 000">
                            @error('phone')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 space-y-2 group">
                            <label for="address" class="text-[10px] uppercase tracking-[0.3em] font-headline font-bold text-outline group-focus-within:text-secondary-container transition-colors">Nút_chính (Địa chỉ)</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}" required autocomplete="street-address"
                                class="{{ $fieldClass }}"
                                placeholder="Phường, đường, tòa nhà, căn hộ…">
                            @error('address')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2 group">
                            <label for="city" class="text-[10px] uppercase tracking-[0.3em] font-headline font-bold text-outline group-focus-within:text-secondary-container transition-colors">Khu_vực_thành_phố</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}" required autocomplete="address-level2"
                                class="{{ $fieldClass }}"
                                placeholder="TP. Hồ Chí Minh">
                            @error('city')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2 group">
                            <label for="postal_code" class="text-[10px] uppercase tracking-[0.3em] font-headline font-bold text-outline group-focus-within:text-secondary-container transition-colors">Mã_bưu_chính</label>
                            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" required autocomplete="postal-code"
                                class="{{ $fieldClass }}"
                                placeholder="700000">
                            @error('postal_code')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </form>
                </section>
            </div>

            {{-- Cột phải: báo cáo tóm tắt + đặt hàng --}}
            <aside class="lg:col-span-4 lg:sticky lg:top-24 lg:self-start">
                <div class="bg-surface-container-high p-6 md:p-8 relative overflow-hidden border border-outline-variant/10 checkout-chamfer">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary-container/10 blur-3xl rounded-full -mr-16 -mt-16 pointer-events-none" aria-hidden="true"></div>
                    <h2 class="font-headline text-xl md:text-2xl font-bold uppercase tracking-tighter text-secondary-container mb-6 md:mb-8 relative">Báo_cáo_tóm_tắt</h2>
                    <div class="space-y-4 font-headline relative text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-outline uppercase tracking-wide">Tạm tính</span>
                            <span class="text-on-surface tabular-nums">{{ number_format($subtotal, 0, ',', '.') }} đ</span>
                        </div>
                        <div class="flex justify-between gap-4">
                            <span class="text-outline uppercase tracking-wide">Phí vận chuyển</span>
                            <span class="text-on-surface tabular-nums">{{ number_format($freight, 0, ',', '.') }} đ</span>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-outline-variant/20 pb-4">
                            <span class="text-outline uppercase tracking-wide">Thuế (nếu có)</span>
                            <span class="text-on-surface tabular-nums">{{ number_format($tax, 0, ',', '.') }} đ</span>
                        </div>
                        <div class="flex justify-between items-baseline gap-4 pt-2">
                            <span class="text-primary font-bold uppercase tracking-[0.15em] text-xs">Tổng_cấp_phát</span>
                            <span class="text-2xl md:text-3xl font-bold text-secondary-fixed drop-shadow-[0_0_10px_rgba(99,247,255,0.35)] tabular-nums">{{ number_format($total, 0, ',', '.') }} đ</span>
                        </div>
                    </div>
                    <div class="mt-8 md:mt-10 space-y-4 relative">
                        <div class="bg-surface-container-lowest p-4 flex items-start gap-3 border border-outline-variant/15">
                            <span class="material-symbols-outlined text-secondary-container shrink-0 text-xl">verified_user</span>
                            <p class="text-[10px] text-outline-variant leading-relaxed uppercase font-headline font-bold tracking-tight">
                                Giao dịch được mã hóa theo phiên làm việc. Toàn vẹn dữ liệu đã xác minh.
                            </p>
                        </div>
                        <button type="submit" form="checkout-form" class="relative w-full overflow-hidden group bg-gradient-to-r from-primary-container to-secondary-container text-on-primary font-headline font-extrabold uppercase tracking-widest py-4 md:py-5 text-xs md:text-sm hover:brightness-110 active:scale-[0.98] transition-all checkout-chamfer shadow-[0_0_24px_rgba(123,47,247,0.25)]">
                            <span class="relative z-10">Xác_nhận_đặt_hàng</span>
                            <span class="pointer-events-none absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-500 skew-x-12" aria-hidden="true"></span>
                        </button>
                        <p class="text-center text-[10px] text-outline-variant uppercase tracking-widest font-headline leading-relaxed px-1">
                            Tiếp tục đồng nghĩa bạn đồng ý với điều khoản dịch vụ cửa hàng.
                        </p>
                    </div>
                </div>

                <div class="mt-4 bg-surface-container-low p-5 md:p-6 border-l-4 border-secondary-container/60 flex items-center justify-between gap-4 checkout-chamfer">
                    <div>
                        <div class="text-[10px] text-outline font-headline font-bold uppercase tracking-widest">Dự_kiến_giao</div>
                        <div class="text-primary font-headline text-base md:text-lg mt-1">24–48 GIỜ</div>
                    </div>
                    <span class="material-symbols-outlined text-3xl md:text-4xl text-outline-variant shrink-0">local_shipping</span>
                </div>
            </aside>
        </div>
    @endif
</div>
@endsection

@extends('layouts.admin-synth')

@section('title', 'Tổng quan')

@push('head')
<style>
    .glow-primary-bar { box-shadow: 0 0 10px rgba(210, 187, 255, 0.45); }
    .glow-secondary-bar { box-shadow: 0 0 10px rgba(0, 244, 254, 0.45); }
    .glow-tertiary-bar { box-shadow: 0 0 10px rgba(255, 172, 232, 0.45); }
</style>
@endpush

@section('content')
    {{-- Header Section — thiết kế admin_system_overview --}}
    <section class="flex flex-col gap-2 mb-6 lg:mb-8">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-headline font-black tracking-tighter text-primary uppercase">Tổng quan hệ thống</h1>
        <div class="flex items-center gap-2 flex-wrap">
            <span class="w-2 h-2 bg-secondary-container animate-pulse shrink-0" aria-hidden="true"></span>
            <span class="text-[10px] font-headline font-bold text-secondary-container tracking-widest uppercase">Trạng thái: Ổn định — Đồng bộ dữ liệu</span>
        </div>
    </section>

    {{-- Stat Cards --}}
    <section class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <div class="bg-surface-container-low border-l-4 border-primary p-5 lg:p-6 relative overflow-hidden group hover:bg-surface-container-high transition-colors">
            <div class="absolute top-0 right-0 p-3 lg:p-4 opacity-10 group-hover:opacity-20 transition-opacity pointer-events-none">
                <span class="material-symbols-outlined text-5xl lg:text-6xl">inventory_2</span>
            </div>
            <div class="relative z-10 flex flex-col gap-3">
                <span class="text-[10px] font-headline font-black text-primary tracking-[0.3em] uppercase">Tổng sản phẩm</span>
                <div class="flex items-baseline gap-2 flex-wrap">
                    <span class="text-3xl lg:text-4xl font-headline font-bold text-on-background tabular-nums">{{ number_format($stats['products']) }}</span>
                    <span class="text-[10px] font-headline font-bold text-secondary-container uppercase tracking-wider">DB</span>
                </div>
                <div class="w-full h-1 bg-[#131313]">
                    <div class="h-full bg-primary glow-primary-bar transition-all duration-500" style="width: {{ $productsPct }}%"></div>
                </div>
            </div>
        </div>
        <div class="bg-surface-container-low border-l-4 border-secondary-container p-5 lg:p-6 relative overflow-hidden group hover:bg-surface-container-high transition-colors">
            <div class="absolute top-0 right-0 p-3 lg:p-4 opacity-10 group-hover:opacity-20 transition-opacity pointer-events-none">
                <span class="material-symbols-outlined text-5xl lg:text-6xl">shopping_cart</span>
            </div>
            <div class="relative z-10 flex flex-col gap-3">
                <span class="text-[10px] font-headline font-black text-secondary-container tracking-[0.3em] uppercase">Tổng đơn hàng</span>
                <div class="flex items-baseline gap-2 flex-wrap">
                    <span class="text-3xl lg:text-4xl font-headline font-bold text-on-background tabular-nums">{{ number_format($stats['orders']) }}</span>
                    <span class="text-[10px] font-headline font-bold text-primary uppercase tracking-wider">LIVE</span>
                </div>
                <div class="w-full h-1 bg-[#131313]">
                    <div class="h-full bg-secondary-container glow-secondary-bar transition-all duration-500" style="width: {{ $ordersPct }}%"></div>
                </div>
            </div>
        </div>
        <div class="bg-surface-container-low border-l-4 border-tertiary p-5 lg:p-6 relative overflow-hidden group hover:bg-surface-container-high transition-colors">
            <div class="absolute top-0 right-0 p-3 lg:p-4 opacity-10 group-hover:opacity-20 transition-opacity pointer-events-none">
                <span class="material-symbols-outlined text-5xl lg:text-6xl">group</span>
            </div>
            <div class="relative z-10 flex flex-col gap-3">
                <span class="text-[10px] font-headline font-black text-tertiary tracking-[0.3em] uppercase">Tổng người dùng</span>
                <div class="flex items-baseline gap-2 flex-wrap">
                    <span class="text-3xl lg:text-4xl font-headline font-bold text-on-background tabular-nums">{{ number_format($stats['users']) }}</span>
                    <span class="text-[10px] font-headline font-bold text-secondary-container uppercase tracking-wider">USER</span>
                </div>
                <div class="w-full h-1 bg-[#131313]">
                    <div class="h-full bg-tertiary glow-tertiary-bar transition-all duration-500" style="width: {{ $usersPct }}%"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Chart — SYSTEM_OUTPUT (SVG giống mẫu) --}}
    <section class="bg-[#1c1c1c] p-5 lg:p-8 border border-outline-variant/20 relative mb-6 lg:mb-8">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary-container via-secondary-container to-tertiary-container pointer-events-none"></div>
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-6 lg:mb-8">
            <div class="space-y-1">
                <h2 class="text-base lg:text-lg font-headline font-black tracking-widest text-primary uppercase">Đơn hàng 7 ngày gần nhất</h2>
                <p class="text-[10px] font-body text-gray-500 uppercase tracking-[0.2em]">7 ngày gần nhất — đếm đơn (cyan) / doanh thu (tím) từ CSDL</p>
            </div>
            <div class="flex gap-4 shrink-0">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-primary"></div>
                    <span class="text-[10px] font-headline font-bold text-gray-400 uppercase">Doanh thu</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-secondary-container"></div>
                    <span class="text-[10px] font-headline font-bold text-gray-400 uppercase">Số đơn</span>
                </div>
            </div>
        </div>
        <div class="h-[220px] sm:h-[300px] lg:h-[400px] w-full relative flex flex-col justify-between pt-2">
            <div class="absolute inset-0 flex flex-col justify-between opacity-10 pointer-events-none">
                @for($i = 0; $i < 5; $i++)
                    <div class="w-full h-px bg-outline"></div>
                @endfor
            </div>
            <div class="absolute inset-0 flex justify-between opacity-10 pointer-events-none">
                @for($i = 0; $i < 6; $i++)
                    <div class="h-full w-px bg-outline"></div>
                @endfor
            </div>
            <svg class="absolute inset-0 w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 1000 400" aria-hidden="true">
                <path class="drop-shadow-[0_0_8px_rgba(0,244,254,0.6)]" d="{{ $chartPathOrders }}" fill="none" stroke="#00f4fe" stroke-width="3" stroke-linejoin="round" stroke-linecap="round"></path>
                <path class="drop-shadow-[0_0_8px_rgba(123,47,247,0.6)]" d="{{ $chartPathRevenue }}" fill="none" stroke="#7b2ff7" stroke-width="3" stroke-linejoin="round" stroke-linecap="round"></path>
            </svg>
            <div class="mt-auto flex justify-between gap-1 text-[9px] sm:text-[10px] font-headline font-bold text-gray-600 pt-3 uppercase tracking-widest relative z-10">
                @foreach($chartDayLabels as $label)
                    <span class="min-w-0 truncate">{{ $label }}</span>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Activity + Hardware --}}
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
        <div class="bg-surface-container-low p-5 lg:p-6 space-y-4 border border-outline-variant/10">
            <div class="flex justify-between items-center border-b border-[#1c1c1c] pb-3">
                <span class="text-xs font-headline font-black text-secondary tracking-widest uppercase">Hoạt động gần đây</span>
                <a href="{{ route('admin.orders.index') }}" class="material-symbols-outlined text-secondary hover:text-primary transition-colors" title="Tất cả đơn">filter_list</a>
            </div>
            <div class="space-y-3 font-headline text-[10px] tracking-widest max-h-[280px] overflow-y-auto admin-core-scroll pr-1">
                @forelse($recentOrders as $o)
                    @php
                        $colors = ['text-secondary-container', 'text-primary-container', 'text-tertiary-container', 'text-error'];
                        $c = $colors[$loop->index % count($colors)];
                        $statusMap = ['pending' => 'PENDING', 'processing' => 'PROC', 'completed' => 'DONE', 'cancelled' => 'VOID'];
                        $st = $statusMap[$o->status] ?? strtoupper($o->status);
                    @endphp
                    <div class="flex gap-3 items-start text-gray-400">
                        <span class="{{ $c }} shrink-0 font-mono">[{{ $o->created_at->format('H:i:s') }}]</span>
                        <p class="leading-relaxed">
                            <span class="text-on-background">Đơn {{ str_pad((string) $o->id, 4, '0', STR_PAD_LEFT) }}</span>
                            {{ $st }} // {{ number_format($o->total_price, 0, ',', '.') }} đ
                            @if($o->user)
                                <span class="text-on-surface-variant"> // {{ $o->user->name }}</span>
                            @endif
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500 text-xs font-headline">Chưa có đơn hàng.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-[#1c1c1c] p-5 lg:p-6 flex flex-col gap-6 border border-outline-variant/10">
            <div>
                <span class="text-xs font-headline font-black text-primary tracking-widest uppercase">Mức tải (ước lượng)</span>
                <p class="text-[9px] text-gray-500 font-headline mt-1 tracking-wide uppercase">Ước lượng từ đơn hôm nay / tồn kho thấp / đơn hoàn thành</p>
            </div>
            <div class="space-y-5">
                <div class="space-y-2">
                    <div class="flex justify-between text-[10px] font-headline font-bold uppercase tracking-widest">
                        <span>CPU (ước lượng)</span>
                        <span class="text-secondary-container">{{ $cpuPct }}%</span>
                    </div>
                    <div class="w-full h-2 bg-[#0e0e0e]">
                        <div class="h-full bg-secondary-container shadow-[0_0_8px_rgba(0,244,254,0.35)] transition-all duration-500" style="width: {{ $cpuPct }}%"></div>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-[10px] font-headline font-bold uppercase tracking-widest">
                        <span>Bộ nhớ (ước lượng)</span>
                        <span class="text-primary">{{ $memPct }}%</span>
                    </div>
                    <div class="w-full h-2 bg-[#0e0e0e]">
                        <div class="h-full bg-primary shadow-[0_0_8px_rgba(210,187,255,0.35)] transition-all duration-500" style="width: {{ $memPct }}%"></div>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-[10px] font-headline font-bold uppercase tracking-widest">
                        <span>Mạng (ước lượng)</span>
                        <span class="text-tertiary">{{ $netPct }}%</span>
                    </div>
                    <div class="w-full h-2 bg-[#0e0e0e]">
                        <div class="h-full bg-tertiary shadow-[0_0_8px_rgba(255,172,232,0.35)] transition-all duration-500" style="width: {{ $netPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

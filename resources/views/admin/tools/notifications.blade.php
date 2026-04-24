@extends('layouts.admin-synth')

@section('title', 'Thông báo')

@section('content')
    <div class="max-w-3xl">
        <header class="mb-8">
            <div class="flex items-center gap-2 mb-2">
                <span class="h-1 w-8 bg-secondary-container shrink-0"></span>
                <span class="text-secondary-container font-headline text-[10px] tracking-[0.35em] uppercase font-bold">Hệ thống</span>
            </div>
            <h1 class="font-headline text-2xl sm:text-3xl font-black text-on-background uppercase tracking-tight">Trung tâm thông báo</h1>
            <p class="text-outline mt-2 text-sm font-body">Các sự kiện và cảnh báo gần đây trong khu vực quản trị.</p>
        </header>

        <div class="border border-outline-variant/20 bg-surface-container-lowest divide-y divide-outline-variant/10">
            @php
                $items = [
                    ['t' => 'Đồng bộ kho', 'm' => 'Dữ liệu sản phẩm đã được đồng bộ với cơ sở dữ liệu.', 'time' => now()->subMinutes(12)->format('H:i d/m/Y')],
                    ['t' => 'Đơn hàng mới', 'm' => 'Có đơn chờ xử lý — kiểm tra mục Đơn hàng.', 'time' => now()->subHours(2)->format('H:i d/m/Y')],
                    ['t' => 'Bảo trì', 'm' => 'Phiên bản giao diện quản trị ổn định. Không có lịch tắt máy.', 'time' => now()->subDay()->format('H:i d/m/Y')],
                ];
            @endphp
            @foreach($items as $i => $row)
                <div class="p-4 sm:p-5 flex gap-4 hover:bg-surface-container-low/80 transition-colors">
                    <div class="shrink-0 w-10 h-10 flex items-center justify-center bg-primary-container/15 border border-primary-container/30 text-primary">
                        <span class="material-symbols-outlined text-xl">notifications_active</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-headline text-sm font-bold text-on-background">{{ $row['t'] }}</p>
                        <p class="text-sm text-on-surface-variant mt-1 leading-relaxed">{{ $row['m'] }}</p>
                        <p class="text-[10px] text-outline font-mono mt-2">{{ $row['time'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <p class="mt-6 text-[11px] text-outline font-body">Gợi ý: sau này có thể kết nối bảng thông báo thật trong cơ sở dữ liệu.</p>
    </div>
@endsection

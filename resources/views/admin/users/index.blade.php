@extends('layouts.admin-synth')

@section('title', 'Quản lý người dùng')

@push('head')
    <style>
        .user-registry-circuit {
            height: 1px;
            width: 100%;
            background: linear-gradient(90deg, transparent, rgba(123, 47, 247, 0.9), transparent);
        }
        @keyframes user-row-in {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .user-registry-row { animation: user-row-in 0.4s ease-out both; }
    </style>
@endpush

@section('content')
    @php
        $exportUrl = route('admin.users.index', array_merge(array_filter(['q' => request('q')]), ['export' => 'csv']));
        $roleBadge = function (\App\Models\User $u): array {
            if ($u->role === 'admin') {
                return ['label' => 'QUẢN TRỊ', 'class' => 'bg-primary-container/25 text-primary border-primary-container/50 shadow-[0_0_12px_rgba(123,47,247,0.2)]'];
            }
            return ['label' => 'KHÁCH', 'class' => 'bg-surface-container-high/80 text-on-surface-variant border-outline-variant/40'];
        };
    @endphp

    @if(session('success'))
        <div class="mb-6 px-4 py-3 border border-secondary-container/40 text-secondary-container text-sm font-headline">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-6 px-4 py-3 border border-error/50 text-error text-sm font-headline">{{ session('error') }}</div>
    @endif

    {{-- Header + nút hành động --}}
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-10 lg:mb-12">
        <div class="max-w-2xl">
            <div class="flex items-center gap-2 mb-2">
                <span class="h-1 w-8 bg-secondary-container shrink-0"></span>
                <span class="text-secondary-container font-headline text-[10px] sm:text-xs tracking-[0.4em] uppercase font-bold">Mức bảo mật: Alpha</span>
            </div>
            <h1 class="font-headline text-3xl sm:text-4xl md:text-5xl font-black text-on-background tracking-tighter uppercase leading-none">Sổ đăng ký người dùng</h1>
            <p class="text-outline mt-3 font-body text-sm leading-relaxed">Giao diện quản lý tài khoản và node hành chính trong kiến trúc cửa hàng. Đồng bộ vai trò, email và nhật ký cập nhật.</p>
        </div>
        <div class="flex flex-wrap gap-3 sm:gap-4 shrink-0">
            <a href="{{ $exportUrl }}" class="inline-flex items-center justify-center px-5 py-3 bg-surface-container-lowest border border-outline-variant hover:border-primary-container/60 hover:text-primary text-primary text-[10px] sm:text-xs font-bold font-headline uppercase tracking-widest transition-all">
                Xuất CSV
            </a>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-primary-container to-secondary-container text-white text-[10px] sm:text-xs font-black font-headline uppercase tracking-[0.18em] shadow-[0_0_24px_rgba(0,244,254,0.28)] hover:brightness-110 hover:shadow-[0_0_32px_rgba(123,47,247,0.35)] transition-all active:scale-[0.98]">
                <span class="material-symbols-outlined text-lg">person_add</span>
                Đăng ký node mới
            </a>
        </div>
    </div>

    {{-- Thẻ thống kê (bento) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-px mb-8 bg-outline-variant/25 p-px border border-outline-variant/20">
        <div class="bg-surface-container-lowest p-5 sm:p-6 border-l-4 border-primary-container">
            <p class="font-headline text-[10px] text-outline tracking-widest uppercase mb-1">Tổng node</p>
            <p class="font-headline text-2xl sm:text-3xl font-bold text-on-background tabular-nums">{{ number_format($totalNodes) }}</p>
        </div>
        <div class="bg-surface-container-lowest p-5 sm:p-6 border-l-4 border-secondary-container">
            <p class="font-headline text-[10px] text-outline tracking-widest uppercase mb-1">Hoạt động 7 ngày</p>
            <p class="font-headline text-2xl sm:text-3xl font-bold text-on-background tabular-nums">{{ number_format($activeSessions) }}</p>
        </div>
        <div class="bg-surface-container-lowest p-5 sm:p-6 border-l-4 border-tertiary">
            <p class="font-headline text-[10px] text-outline tracking-widest uppercase mb-1">Quản trị hệ thống</p>
            <p class="font-headline text-2xl sm:text-3xl font-bold text-on-background tabular-nums">{{ number_format($systemAdmins) }}</p>
        </div>
        <div class="bg-surface-container-lowest p-5 sm:p-6 border-l-4 border-error/80">
            <p class="font-headline text-[10px] text-outline tracking-widest uppercase mb-1">Xác thực thất bại (ước tính)</p>
            <p class="font-headline text-2xl sm:text-3xl font-bold text-on-background tabular-nums">{{ $failedAuthPct }}%</p>
        </div>
    </div>

    {{-- Bảng --}}
    <div class="bg-surface-container-lowest border border-outline-variant/30 relative overflow-hidden">
        <div class="user-registry-circuit opacity-40 absolute top-0 left-0 right-0 pointer-events-none"></div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-surface-container text-outline font-headline text-[10px] tracking-[0.2em] uppercase">
                        <th class="px-4 sm:px-6 py-4 border-b border-outline-variant/20 font-black">Người dùng</th>
                        <th class="px-4 sm:px-6 py-4 border-b border-outline-variant/20 font-black">Email</th>
                        <th class="px-4 sm:px-6 py-4 border-b border-outline-variant/20 font-black">Vai trò</th>
                        <th class="px-4 sm:px-6 py-4 border-b border-outline-variant/20 font-black">Cập nhật cuối</th>
                        <th class="px-4 sm:px-6 py-4 border-b border-outline-variant/20 font-black text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($users as $user)
                        @php
                            $rb = $roleBadge($user);
                            $uid = 'UID: '.str_pad((string) $user->id, 3, '0', STR_PAD_LEFT).'-'.strtoupper(substr(md5((string) $user->id), 0, 2));
                            $nodeName = mb_strtoupper(preg_replace('/\s+/', '_', trim($user->name)) ?: 'USER');
                            $lastSync = $user->updated_at
                                ? $user->updated_at->format('H:i:s').' // '.$user->updated_at->format('m.d.y')
                                : '—';
                        @endphp
                        <tr class="user-registry-row hover:bg-surface-container-low/90 transition-colors group" style="animation-delay: {{ min($loop->index * 35, 480) }}ms;">
                            <td class="px-4 sm:px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-surface-container-highest border border-primary/25 flex items-center justify-center shrink-0 text-xs font-black font-headline text-primary group-hover:border-secondary-container/50 transition-colors">
                                        {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm font-bold text-on-background font-headline uppercase tracking-tight truncate max-w-[12rem]" title="{{ $user->name }}">{{ $nodeName }}</div>
                                        <div class="text-[10px] text-primary uppercase font-headline tracking-tight">{{ $uid }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-5">
                                <span class="text-xs font-body text-outline break-all">{{ $user->email }}</span>
                            </td>
                            <td class="px-4 sm:px-6 py-5">
                                <span class="inline-block px-2.5 py-1 text-[10px] font-black uppercase tracking-widest border {{ $rb['class'] }}">{{ $rb['label'] }}</span>
                            </td>
                            <td class="px-4 sm:px-6 py-5 font-mono text-[11px] text-on-surface-variant tabular-nums">{{ $lastSync }}</td>
                            <td class="px-4 sm:px-6 py-5 text-right">
                                <div class="flex justify-end items-center gap-1 opacity-70 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-gray-500 hover:text-secondary-container border border-transparent hover:border-outline-variant/40 transition-colors" title="Sửa">
                                        <span class="material-symbols-outlined text-xl">edit</span>
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="post" class="inline"
                                            data-synth-confirm
                                            data-confirm-title="Xóa người dùng?"
                                            data-confirm-message="Hành động không hoàn tác. Đơn hàng và giỏ hàng liên quan sẽ bị xóa theo ràng buộc cơ sở dữ liệu.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-500 hover:text-error border border-transparent hover:border-error/30 transition-colors" title="Xóa">
                                                <span class="material-symbols-outlined text-xl">delete</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="p-2 text-outline/40 cursor-not-allowed" title="Không xóa chính mình tại đây">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 px-6 text-center text-on-surface-variant font-headline text-sm">Không có người dùng khớp tìm kiếm.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->total() > 0)
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-4 sm:px-6 py-4 border-t border-outline-variant/10 bg-surface-container-low/50">
                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest text-center sm:text-left">
                    Hiển thị {{ $users->firstItem() }}–{{ $users->lastItem() }} / {{ number_format($users->total()) }} node
                </div>
                <div class="flex justify-center sm:justify-end w-full sm:w-auto">
                    {{ $users->links('vendor.pagination.synth-inventory') }}
                </div>
            </div>
        @endif
    </div>
@endsection

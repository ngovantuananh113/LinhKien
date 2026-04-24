@extends('layouts.admin-synth')

@section('title', 'Danh mục')

@section('content')
    <header class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-8 lg:mb-12">
        <div class="space-y-2">
            <div class="flex items-center gap-2 text-secondary-container font-headline text-[10px] tracking-[0.35em] uppercase">
                <span class="w-8 h-[1px] bg-secondary-container"></span>
                Quản lý /
            </div>
            <h1 class="text-3xl sm:text-4xl font-black font-headline tracking-tight text-on-background uppercase">Danh mục sản phẩm</h1>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="group relative inline-flex items-center justify-center gap-2 px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-primary-container to-secondary-container text-white font-headline font-bold text-[10px] sm:text-xs tracking-widest uppercase shadow-[0_0_20px_rgba(0,244,254,0.3)] hover:shadow-[0_0_35px_rgba(0,244,254,0.45)] transition-all duration-150">
            <span class="material-symbols-outlined text-base sm:text-lg">add_circle</span>
            Thêm danh mục
        </a>
    </header>

    <div class="relative bg-surface-container-lowest border border-outline-variant/10 chamfer-tr-bl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[720px]">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/20">
                        <th class="py-5 px-4 sm:px-6 font-headline text-[10px] tracking-[0.3em] uppercase text-primary">Mã</th>
                        <th class="py-5 px-4 sm:px-6 font-headline text-[10px] tracking-[0.3em] uppercase text-primary">Tên danh mục</th>
                        <th class="py-5 px-4 sm:px-6 font-headline text-[10px] tracking-[0.3em] uppercase text-primary">Mô tả</th>
                        <th class="py-5 px-4 sm:px-6 font-headline text-[10px] tracking-[0.3em] uppercase text-primary text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5">
                    @php $icons = ['fluid', 'ac_unit', 'hub', 'shield']; @endphp
                    @forelse($categories as $cat)
                        @php $ic = $icons[$loop->index % 4]; @endphp
                        <tr class="hover:bg-surface-container-high/50 transition-colors group">
                            <td class="py-5 sm:py-6 px-4 sm:px-6 font-headline text-[10px] text-secondary-container/70">CAT-{{ str_pad((string) $cat->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-5 sm:py-6 px-4 sm:px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-surface-container-highest flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings:'FILL'1">{{ $ic }}</span>
                                    </div>
                                    <span class="font-headline font-bold text-base sm:text-lg tracking-tight uppercase">{{ $cat->name }}</span>
                                </div>
                            </td>
                            <td class="py-5 sm:py-6 px-4 sm:px-6 max-w-md">
                                <p class="text-on-surface-variant text-sm leading-relaxed">{{ \Illuminate\Support\Str::limit($cat->description ?? '—', 160) }}</p>
                            </td>
                            <td class="py-5 sm:py-6 px-4 sm:px-6 text-right">
                                <div class="flex justify-end gap-2 opacity-40 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.categories.edit', $cat) }}" class="w-10 h-10 bg-surface-container-highest text-secondary-container hover:bg-secondary-container hover:text-[#002021] transition-all flex items-center justify-center" title="Sửa">
                                        <span class="material-symbols-outlined text-xl">edit_square</span>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="post" class="inline"
                                        data-synth-confirm
                                        data-confirm-title="Xóa danh mục?"
                                        data-confirm-message="Chỉ thực hiện được khi không còn sản phẩm gắn với danh mục này.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-10 h-10 bg-surface-container-highest text-error hover:bg-error hover:text-[#131313] transition-all flex items-center justify-center" title="Xóa">
                                            <span class="material-symbols-outlined text-xl">delete_forever</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 px-6 text-center text-on-surface-variant font-headline text-sm">Chưa có danh mục.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->total() > 0)
            <div class="bg-surface-container-low px-4 sm:px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-4 border-t border-outline-variant/10">
                <div class="text-[10px] font-headline text-gray-500 tracking-widest uppercase text-center sm:text-left">
                    Hiển thị {{ $categories->firstItem() }}–{{ $categories->lastItem() }} / {{ $categories->total() }} mục — Trang {{ str_pad((string) $categories->currentPage(), 2, '0', STR_PAD_LEFT) }}
                </div>
                <div class="w-full sm:w-auto flex justify-center sm:justify-end">
                    {{ $categories->links('vendor.pagination.synth') }}
                </div>
            </div>
        @endif
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 bg-surface-container-low border-l-2 border-primary-container relative overflow-hidden">
            <div class="relative z-10">
                <div class="text-[10px] font-headline text-primary-container tracking-widest uppercase mb-1">Danh mục hoạt động</div>
                <div class="text-3xl font-black font-headline tabular-nums">{{ number_format($stats['categories']) }}</div>
            </div>
            <span class="absolute top-[-20px] right-[-10px] material-symbols-outlined text-white/5 text-8xl pointer-events-none" style="font-variation-settings:'FILL'1">category</span>
        </div>
        <div class="p-6 bg-surface-container-low border-l-2 border-secondary-container relative overflow-hidden">
            <div class="relative z-10">
                <div class="text-[10px] font-headline text-secondary-container tracking-widest uppercase mb-1">Tải hệ thống</div>
                <div class="text-3xl font-black font-headline tabular-nums">{{ $systemLoadPct }}%</div>
            </div>
            <span class="absolute top-[-20px] right-[-10px] material-symbols-outlined text-white/5 text-8xl pointer-events-none" style="font-variation-settings:'FILL'1">monitoring</span>
        </div>
        <div class="p-6 bg-surface-container-low border-l-2 border-tertiary relative overflow-hidden">
            <div class="relative z-10">
                <div class="text-[10px] font-headline text-tertiary tracking-widest uppercase mb-1">Triển khai gần nhất</div>
                <div class="text-xl font-black font-headline tracking-tight">{{ $lastDeployFormatted }}</div>
            </div>
            <span class="absolute top-[-20px] right-[-10px] material-symbols-outlined text-white/5 text-8xl pointer-events-none" style="font-variation-settings:'FILL'1">timer</span>
        </div>
    </div>
@endsection

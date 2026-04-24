@php
    $sectorStyles = [
        ['text' => 'text-primary', 'border' => 'border-primary/30', 'bg' => 'bg-primary/5'],
        ['text' => 'text-tertiary', 'border' => 'border-tertiary/30', 'bg' => 'bg-tertiary/5'],
        ['text' => 'text-secondary-container', 'border' => 'border-secondary-container/30', 'bg' => 'bg-secondary-container/5'],
    ];
@endphp
<div class="admin-inv-table-wrap bg-surface-container-low overflow-hidden border border-outline-variant/10 shadow-[inset_0_1px_0_rgba(255,255,255,0.03)]">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[640px]">
            <thead>
                <tr class="bg-surface-container-lowest border-b border-surface-container-high">
                    <th class="px-4 sm:px-6 py-4 text-[10px] font-bold text-gray-500 uppercase tracking-[0.15em]">Ảnh</th>
                    <th class="px-4 sm:px-6 py-4 text-[10px] font-bold text-gray-500 uppercase tracking-[0.15em]">Tên &amp; mã SKU</th>
                    <th class="px-4 sm:px-6 py-4 text-[10px] font-bold text-gray-500 uppercase tracking-[0.15em]">Giá</th>
                    <th class="px-4 sm:px-6 py-4 text-[10px] font-bold text-gray-500 uppercase tracking-[0.15em]">Tồn</th>
                    <th class="px-4 sm:px-6 py-4 text-[10px] font-bold text-gray-500 uppercase tracking-[0.15em]">Danh mục</th>
                    <th class="px-4 sm:px-6 py-4 text-[10px] font-bold text-gray-500 uppercase tracking-[0.15em] text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-container">
                @forelse ($products as $product)
                    @php
                        $cid = (int) ($product->category_id ?? 0);
                        $st = $sectorStyles[$cid % 3];
                        $sectorLabel = $product->category?->name ?? 'Chưa phân loại';
                        $sku = 'SKU_'.str_pad((string) $product->id, 4, '0', STR_PAD_LEFT).'_'.strtoupper(substr(md5((string) $product->id), 0, 2));
                        $qtyStr = str_pad((string) $product->quantity, 2, '0', STR_PAD_LEFT);
                        $low = (int) $product->quantity < 10;
                        $isRowEdit = (int) request('edit') === (int) $product->id;
                    @endphp
                    <tr
                        class="admin-inv-row group hover:bg-surface-container-highest/30 transition-colors duration-300 {{ $isRowEdit ? 'bg-primary-container/10 ring-1 ring-inset ring-secondary-container/40' : '' }}"
                        style="animation-delay: {{ min($loop->index * 45, 600) }}ms;"
                    >
                        <td class="px-4 sm:px-6 py-4 align-middle">
                            <div class="w-12 h-12 bg-black border border-outline-variant group-hover:border-secondary-container transition-all duration-300 relative overflow-hidden shrink-0 group-hover:scale-105">
                                @if ($product->imageUrl())
                                    <img src="{{ $product->imageUrl() }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <span class="absolute inset-0 flex items-center justify-center text-gray-600">
                                        <span class="material-symbols-outlined text-2xl">memory</span>
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-4">
                            <div class="font-headline font-bold text-white text-sm">{{ $product->name }}</div>
                            <div class="text-[10px] text-gray-500 mt-0.5 font-mono">Mã: {{ $sku }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 font-body text-sm text-secondary-container tabular-nums">{{ number_format((float) $product->price, 0, ',', '.') }} đ</td>
                        <td class="px-4 sm:px-6 py-4">
                            @if ($low)
                                <span class="inline-block bg-red-900/90 text-[10px] font-bold px-2 py-1 text-white tabular-nums animate-pulse" title="Tồn thấp">{{ $qtyStr }} đv</span>
                            @else
                                <span class="inline-block bg-surface-container-highest text-[10px] font-bold px-2 py-1 text-on-surface tabular-nums">{{ $qtyStr }} đv</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-4">
                            <span class="inline-block text-[10px] font-bold tracking-wide border px-2 py-1 max-w-[10rem] truncate {{ $st['text'] }} {{ $st['border'] }} {{ $st['bg'] }}" title="{{ $sectorLabel }}">{{ $sectorLabel }}</span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 text-right">
                            <div class="flex justify-end gap-1 opacity-60 group-hover:opacity-100 transition-opacity duration-300">
                                <a href="{{ route('admin.products.index', array_filter(['edit' => $product->id, 'q' => request('q'), 'page' => request('page', $products->currentPage())])) }}" class="js-product-edit p-2 text-gray-500 hover:text-secondary-container transition-colors rounded-none hover:bg-surface-container-highest" title="Sửa" data-product-edit="1">
                                    <span class="material-symbols-outlined text-lg">edit_square</span>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="post" class="inline js-product-delete-form" data-ajax-delete="1">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="return_q" value="{{ request('q') }}">
                                    <input type="hidden" name="return_page" value="{{ request('page') ?: $products->currentPage() }}">
                                    <button type="submit" class="p-2 text-gray-500 hover:text-error transition-colors hover:bg-surface-container-highest" title="Xóa">
                                        <span class="material-symbols-outlined text-lg">delete_forever</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-14 px-6 text-center text-on-surface-variant font-headline text-sm">Chưa có sản phẩm. Dùng form bên trái để thêm mặt hàng đầu tiên.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($products->total() > 0)
        <div class="p-4 sm:p-6 bg-surface-container-lowest flex flex-col sm:flex-row justify-between items-center gap-4 border-t border-outline-variant/10">
            <div class="text-[10px] font-bold text-gray-600 uppercase tracking-widest text-center sm:text-left">
                Đang hiển thị: {{ $products->firstItem() }}–{{ $products->lastItem() }} // Tổng: {{ $products->total() }}
            </div>
            <div class="flex justify-center sm:justify-end w-full sm:w-auto admin-product-pagination">
                {{ $products->links('vendor.pagination.synth-inventory') }}
            </div>
        </div>
    @endif
</div>

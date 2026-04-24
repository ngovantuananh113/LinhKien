@php
    $e = $editingProduct ?? null;
@endphp
<div class="relative bg-surface-container-lowest p-6 sm:p-8 border-l-4 border-primary-container overflow-hidden shadow-[0_0_40px_rgba(0,0,0,0.35)] transition-shadow duration-500 hover:shadow-[0_0_50px_rgba(123,47,247,0.12)]">
    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary-container/15 to-transparent -rotate-45 translate-x-10 -translate-y-10 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-24 h-24 bg-secondary-container/5 blur-2xl pointer-events-none"></div>

    <div class="flex flex-wrap items-start justify-between gap-3 mb-6 relative z-10">
        <h2 class="font-headline text-lg sm:text-xl font-bold text-white flex items-center gap-3">
            <span class="material-symbols-outlined text-primary-container transition-transform duration-300 hover:scale-110">{{ $isEdit ? 'edit_note' : 'add_box' }}</span>
            <span>{{ $isEdit ? 'Sửa sản phẩm' : 'Thêm sản phẩm mới' }}</span>
        </h2>
        @if ($isEdit && $e)
            <span class="text-[10px] font-headline font-bold uppercase tracking-widest px-2 py-1 bg-secondary-container/15 text-secondary-container border border-secondary-container/30">Đang sửa #{{ $e->id }}</span>
        @endif
    </div>

    @if ($errors->any())
        <div id="product-form-errors" class="mb-6 p-4 border border-error/40 bg-error/10 text-error text-xs font-headline space-y-1 relative z-10 animate-pulse">
            @foreach ($errors->all() as $err)
                <div>{{ $err }}</div>
            @endforeach
        </div>
    @endif

    <form
        id="admin-product-form"
        action="{{ $isEdit && $e ? route('admin.products.update', $e) : route('admin.products.store') }}"
        method="post"
        enctype="multipart/form-data"
        class="space-y-6 relative z-10"
        data-ajax="1"
    >
        @csrf
        @if ($isEdit && $e)
            @method('PUT')
        @endif
        <input type="hidden" name="return_q" value="{{ request('q') }}">
        <input type="hidden" name="return_page" value="{{ request('page', $productsCurrentPage ?? 1) }}">

        <div class="space-y-1.5">
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block" for="product-name">Tên sản phẩm</label>
            <input id="product-name" type="text" name="name" value="{{ $fvName }}" required maxlength="255"
                class="w-full bg-surface-container border-0 border-b-2 border-outline-variant focus:border-secondary-container text-white py-3 px-1 font-body placeholder:text-gray-600 transition-all duration-300 focus:px-2">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block" for="product-price">Giá (đ)</label>
                <input id="product-price" type="number" name="price" value="{{ $fvPrice }}" min="0" step="1" required
                    class="w-full bg-surface-container border-0 border-b-2 border-outline-variant focus:border-secondary-container text-white py-3 px-1 font-body tabular-nums placeholder:text-gray-600 transition-all duration-300 focus:px-2"
                    placeholder="0">
            </div>
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block" for="product-qty">Số lượng tồn</label>
                <input id="product-qty" type="number" name="quantity" value="{{ $fvQty }}" min="0" required
                    class="w-full bg-surface-container border-0 border-b-2 border-outline-variant focus:border-secondary-container text-white py-3 px-1 font-body tabular-nums placeholder:text-gray-600 transition-all duration-300 focus:px-2"
                    placeholder="0">
            </div>
        </div>

        <div class="space-y-2">
            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block">Danh mục phần cứng</span>
            @if ($categories->isEmpty())
                <p class="text-xs text-error font-headline p-3 border border-error/30 bg-error/5">Chưa có danh mục nào. Hãy tạo danh mục trong mục <strong>Danh mục</strong> trước khi thêm sản phẩm.</p>
            @endif
            <div class="admin-category-select relative group {{ $categories->isEmpty() ? 'opacity-50 pointer-events-none' : '' }}">
                <div class="admin-category-glow absolute -inset-[1px] bg-gradient-to-r from-primary-container/40 via-secondary-container/30 to-primary-container/40 opacity-0 transition-opacity duration-500 pointer-events-none blur-sm"></div>
                <div class="relative flex items-stretch border border-outline-variant/50 bg-surface-container transition-all duration-300 group-focus-within:border-secondary-container group-focus-within:shadow-[0_0_28px_rgba(0,244,254,0.12)] group-hover:border-outline">
                    <span class="flex items-center justify-center w-12 shrink-0 bg-surface-container-high/60 border-r border-outline-variant/30 text-primary-container transition-colors group-focus-within:text-secondary-container">
                        <span class="material-symbols-outlined text-2xl">precision_manufacturing</span>
                    </span>
                    <div class="relative flex-1 min-w-0">
                        <select id="product-category" name="category_id" required
                            class="w-full min-h-[3.25rem] bg-transparent border-0 text-white py-3 pl-3 pr-10 font-body text-sm cursor-pointer focus:ring-0 focus:outline-none appearance-none">
                            <option value="" disabled @selected($fvCat === null || $fvCat === '')>— Chọn danh mục —</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected((string) $fvCat === (string) $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-secondary-container transition-colors">
                            <span class="material-symbols-outlined text-xl">expand_more</span>
                        </span>
                    </div>
                </div>
                <p class="mt-1.5 text-[10px] text-gray-600 font-headline">Chọn đúng nhóm linh kiện để khách hàng lọc và tìm kiếm dễ hơn.</p>
            </div>
        </div>

        <div class="space-y-1.5">
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block" for="product-desc">Mô tả</label>
            <textarea id="product-desc" name="description" rows="4"
                class="w-full bg-surface-container border-0 border-b-2 border-outline-variant focus:border-secondary-container text-white py-3 px-1 font-body placeholder:text-gray-600 transition-all duration-300 resize-y min-h-[6rem] focus:px-2"
                placeholder="Thông số, bảo hành, ghi chú...">{{ $fvDesc }}</textarea>
        </div>

        @if ($isEdit && $e && $e->imageUrl())
            <div id="product-current-image-box" class="flex items-center gap-4 p-3 bg-surface-container-high/40 border border-outline-variant/30">
                <div class="w-16 h-16 border border-outline-variant/40 overflow-hidden shrink-0">
                    <img src="{{ $e->imageUrl() }}" alt="" class="w-full h-full object-cover">
                </div>
                <p class="text-[11px] text-on-surface-variant leading-snug">Ảnh hiện tại. Chọn ảnh mới bên dưới nếu muốn thay thế.</p>
            </div>
        @endif

        <div>
            <label for="product-image" class="group relative block bg-surface-container border-2 border-dashed border-outline-variant p-6 text-center cursor-pointer hover:border-primary-container hover:bg-primary-container/5 transition-all duration-300">
                <span class="material-symbols-outlined text-3xl text-gray-600 group-hover:text-primary group-hover:scale-110 transition-transform duration-300 mb-2 block pointer-events-none">upload_file</span>
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest pointer-events-none">Tải ảnh sản phẩm (tuỳ chọn)</span>
                <input id="product-image" type="file" name="image" accept="image/*" class="sr-only">
            </label>
            <p id="product-image-filename" class="mt-2 text-[10px] text-secondary-container/90 font-headline truncate min-h-[1rem]" aria-live="polite"></p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <button type="button" id="product-form-cancel" class="inline-flex flex-1 items-center justify-center py-3.5 px-4 border border-outline-variant/50 text-on-surface-variant font-headline text-xs font-bold uppercase tracking-widest hover:border-error/50 hover:text-error hover:bg-error/5 transition-all duration-300">
                Hủy
            </button>
            <button type="submit" @disabled($categories->isEmpty()) class="flex-[2] inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary-container to-secondary-container text-white font-headline font-black py-3.5 px-4 uppercase tracking-[0.15em] text-xs hover:shadow-[0_0_32px_rgba(123,47,247,0.45)] transition-all duration-300 relative overflow-hidden group disabled:opacity-40 disabled:pointer-events-none">
                <span class="relative z-10">{{ $isEdit ? 'Cập nhật sản phẩm' : 'Thêm sản phẩm' }}</span>
                <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></span>
            </button>
        </div>
    </form>
</div>

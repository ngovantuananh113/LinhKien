@extends('layouts.admin-synth')

@section('title', 'Sửa sản phẩm')

@section('content')
    <header class="mb-8">
        <div class="text-secondary-container font-label text-[10px] tracking-[0.35em] uppercase mb-2">Kho hàng /</div>
        <h1 class="text-2xl sm:text-3xl font-black font-headline uppercase">Sửa sản phẩm</h1>
    </header>
    <div class="max-w-xl border border-outline-variant/20 bg-surface-container-lowest p-6 sm:p-8">
        <form action="{{ route('admin.products.update', $product) }}" method="post" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-label text-[10px] tracking-widest uppercase text-primary mb-2">Tên *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full bg-surface-container-low border border-outline-variant/30 px-4 py-3 focus:border-secondary-container focus:ring-0">
            </div>
            <div>
                <label class="block font-label text-[10px] tracking-widest uppercase text-primary mb-2">Danh mục *</label>
                <select name="category_id" required class="w-full bg-surface-container-low border border-outline-variant/30 px-4 py-3 focus:border-secondary-container focus:ring-0">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label text-[10px] tracking-widest uppercase text-primary mb-2">Giá *</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="1000" required class="w-full bg-surface-container-low border border-outline-variant/30 px-4 py-3 focus:border-secondary-container focus:ring-0">
                </div>
                <div>
                    <label class="block font-label text-[10px] tracking-widest uppercase text-primary mb-2">Tồn *</label>
                    <input type="number" name="quantity" value="{{ old('quantity', $product->quantity) }}" min="0" required class="w-full bg-surface-container-low border border-outline-variant/30 px-4 py-3 focus:border-secondary-container focus:ring-0">
                </div>
            </div>
            <div>
                <label class="block font-label text-[10px] tracking-widest uppercase text-primary mb-2">Mô tả</label>
                <textarea name="description" rows="4" class="w-full bg-surface-container-low border border-outline-variant/30 px-4 py-3 focus:border-secondary-container focus:ring-0">{{ old('description', $product->description) }}</textarea>
            </div>
            @if($product->imageUrl())
                <div><span class="font-label text-[10px] uppercase text-primary">Ảnh hiện tại</span><br><img src="{{ $product->imageUrl() }}" class="mt-2 max-h-32 border border-outline-variant/20"></div>
            @endif
            <div>
                <label class="block font-label text-[10px] tracking-widest uppercase text-primary mb-2">Ảnh mới</label>
                <input type="file" name="image" accept="image/*" class="w-full text-sm text-on-surface-variant">
            </div>
            @foreach($errors->all() as $e)
                <p class="text-error text-sm">{{ $e }}</p>
            @endforeach
            <div class="flex gap-3">
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-primary-container to-secondary-container text-white font-label text-xs font-bold uppercase tracking-widest">Cập nhật</button>
                <a href="{{ route('admin.products.index') }}" class="px-8 py-3 border border-outline-variant/40 text-on-surface-variant font-label text-xs uppercase">Hủy</a>
            </div>
        </form>
    </div>
@endsection

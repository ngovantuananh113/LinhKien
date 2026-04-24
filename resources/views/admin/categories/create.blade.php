@extends('layouts.admin-synth')

@section('title', 'Thêm danh mục')

@push('head')
    <style>
        .cat-form-glow {
            box-shadow: 0 0 0 1px rgba(123, 47, 247, 0.12), 0 24px 64px rgba(0, 0, 0, 0.55), 0 0 100px rgba(0, 244, 254, 0.06);
        }
        .cat-form-circuit {
            height: 2px;
            width: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 244, 254, 0.5), rgba(123, 47, 247, 0.6), transparent);
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[min(78vh,calc(100vh-10rem))] py-8 sm:py-12 px-4 sm:px-6">
        <div class="w-full max-w-lg">
            <header class="text-center mb-8 sm:mb-10">
                <div class="cat-form-circuit mb-6 max-w-xs mx-auto opacity-80"></div>
                <p class="text-secondary-container font-headline text-[10px] tracking-[0.4em] uppercase font-bold mb-2">Danh mục / Thêm mới</p>
                <h1 class="font-headline text-2xl sm:text-3xl md:text-4xl font-black text-on-background uppercase tracking-tight">Thêm danh mục</h1>
                <p class="text-outline mt-3 text-sm font-body max-w-md mx-auto leading-relaxed">Nhóm sản phẩm trong kho — dùng để phân loại linh kiện trên cửa hàng.</p>
            </header>

            <div class="border border-outline-variant/25 bg-[#0a0a0a] cat-form-glow relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-primary-container via-secondary-container/80 to-transparent opacity-70"></div>
                <div class="p-6 sm:p-9 pl-7 sm:pl-11">
                    <form action="{{ route('admin.categories.store') }}" method="post" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block font-label text-[10px] tracking-widest uppercase text-primary mb-2">Tên danh mục *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full bg-surface-container-low border border-outline-variant/35 px-4 py-3.5 text-on-background focus:border-secondary-container focus:ring-0 focus:outline-none font-body transition-colors">
                            @error('name')
                                <p class="text-error text-sm mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="description" class="block font-label text-[10px] tracking-widest uppercase text-primary mb-2">Mô tả</label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full bg-surface-container-low border border-outline-variant/35 px-4 py-3.5 text-on-background focus:border-secondary-container focus:ring-0 focus:outline-none font-body transition-colors">{{ old('description') }}</textarea>
                        </div>
                        <div class="flex flex-col sm:flex-row flex-wrap gap-3 pt-2">
                            <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center px-10 py-3.5 min-h-[48px] bg-gradient-to-r from-primary-container to-secondary-container text-white font-label text-xs font-black uppercase tracking-[0.2em] shadow-[0_0_28px_rgba(0,244,254,0.25)] hover:brightness-110 active:scale-[0.99] transition-all">Lưu danh mục</button>
                            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center justify-center px-8 py-3.5 min-h-[48px] border border-outline-variant/45 text-on-surface-variant hover:text-primary hover:border-primary-container/40 font-label text-xs font-bold uppercase tracking-widest transition-colors">Quay lại danh sách</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

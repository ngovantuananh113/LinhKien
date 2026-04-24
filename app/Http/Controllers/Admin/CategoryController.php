<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim();
        $categories = Category::query()
            ->when($q !== '', fn ($query) => $query->where('name', 'like', '%'.$q.'%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'categories' => Category::query()->count(),
            'products' => Product::query()->count(),
        ];

        $dailyCounts = Order::query()
            ->where('created_at', '>=', now()->subDays(7)->startOfDay())
            ->get()
            ->groupBy(fn ($o) => $o->created_at->format('Y-m-d'))
            ->map->count();
        $maxDaily = max($dailyCounts->max() ?? 0, 1);
        $todayCount = Order::query()->whereDate('created_at', today())->count();
        $systemLoadPct = min(100, (int) round($todayCount / $maxDaily * 100));

        $lastDeploy = collect([
            Category::query()->max('updated_at'),
            Product::query()->max('updated_at'),
            Order::query()->max('created_at'),
        ])->filter()->max();

        $lastDeployFormatted = $lastDeploy
            ? Carbon::parse($lastDeploy)->timezone(config('app.timezone'))->format('h:i_A')
            : '—';

        return view('admin.categories.index', compact(
            'categories',
            'stats',
            'systemLoadPct',
            'lastDeployFormatted',
        ));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Category::query()->create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Đã thêm danh mục.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Đã cập nhật danh mục.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return redirect()->route('admin.categories.index')->with('error', 'Không xóa được: còn sản phẩm thuộc danh mục này.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa danh mục.');
    }
}

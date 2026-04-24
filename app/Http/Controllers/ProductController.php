<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    private const PRICE_MAX_DEFAULT = 50000000;

    public function index(Request $request): View
    {
        $query = Product::query()->with('category');

        if ($request->filled('q')) {
            $q = $request->string('q')->trim();
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('name', 'like', '%'.$q.'%')
                    ->orWhere('description', 'like', '%'.$q.'%');
            });
        }

        $categoryIds = [];
        if ($request->filled('categories')) {
            $raw = $request->input('categories', []);
            $categoryIds = array_values(array_filter(array_map('intval', is_array($raw) ? $raw : [$raw])));
        } elseif ($request->filled('category')) {
            $categoryIds = [$request->integer('category')];
        }
        if (count($categoryIds) > 0) {
            $query->whereIn('category_id', $categoryIds);
        }

        if ($request->filled('price_max')) {
            $max = min($request->integer('price_max'), self::PRICE_MAX_DEFAULT);
            if ($max > 0) {
                $query->where('price', '<=', $max);
            }
        }

        $archs = $request->input('arch', []);
        if (is_array($archs) && count($archs) > 0) {
            $allowed = ['NVIDIA', 'AMD', 'INTEL', 'ARM'];
            $archs = array_values(array_intersect($allowed, $archs));
            if (count($archs) > 0) {
                $map = ['NVIDIA' => 'NVIDIA', 'AMD' => 'AMD', 'INTEL' => 'Intel', 'ARM' => 'ARM'];
                $query->where(function ($q) use ($archs, $map) {
                    foreach ($archs as $a) {
                        $t = $map[$a] ?? null;
                        if ($t) {
                            $q->orWhere('name', 'like', '%'.$t.'%')
                                ->orWhere('description', 'like', '%'.$t.'%');
                        }
                    }
                });
            }
        }

        switch ($request->string('sort')->toString()) {
            case 'price_asc':
                $query->orderBy('price');
                break;
            case 'price_desc':
                $query->orderByDesc('price');
                break;
            case 'name':
                $query->orderBy('name');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::query()->orderBy('name')->get();

        if ($request->boolean('partial')) {
            return view('user.products.partials.catalog-inner', compact('products', 'categories'));
        }

        return view('user.products.index', compact('products', 'categories'));
    }

    public function show(Product $product): View
    {
        $product->load('category');

        $relatedQuery = Product::query()
            ->with('category')
            ->where('id', '!=', $product->id)
            ->where('quantity', '>', 0);

        $relatedProducts = (clone $relatedQuery)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->orderBy('name')
            ->limit(4)
            ->get();

        if ($relatedProducts->count() < 4) {
            $ids = $relatedProducts->pluck('id')->push($product->id);
            $more = Product::query()
                ->with('category')
                ->whereNotIn('id', $ids)
                ->where('quantity', '>', 0)
                ->orderBy('name')
                ->limit(4 - $relatedProducts->count())
                ->get();
            $relatedProducts = $relatedProducts->concat($more);
        }

        return view('user.products.show', compact('product', 'relatedProducts'));
    }
}

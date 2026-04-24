<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()->orderBy('name')->take(6)->get();

        $featured = Product::query()
            ->with('category')
            ->where('quantity', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        return view('user.home', compact('featured', 'categories'));
    }

    public function newsletter(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đăng ký. Chúng tôi sẽ gửi thông tin khuyến mãi qua email.');
    }
}

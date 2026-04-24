<?php

use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminToolsController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/dang-ky-nhan-tin', [HomeController::class, 'newsletter'])->name('newsletter.subscribe');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/items', [CartController::class, 'store'])->name('cart.items.store');
    Route::patch('/cart/items/{cartItem}', [CartController::class, 'update'])->name('cart.items.update');
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'destroy'])->name('cart.items.destroy');

    Route::get('/checkout', [OrderController::class, 'create'])->name('checkout.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

Route::redirect('/admin', '/quan-tri', 301);

Route::middleware(['auth', 'admin'])->prefix('quan-tri')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('thong-bao', [AdminToolsController::class, 'notifications'])->name('notifications');
    Route::get('dong-lenh', [AdminToolsController::class, 'terminal'])->name('terminal');
    Route::get('cai-dat', [AdminToolsController::class, 'settings'])->name('settings');
    Route::get('ho-so', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('ho-so', [AdminProfileController::class, 'update'])->name('profile.update');

    Route::get('danh-muc', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('danh-muc/them', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('danh-muc', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('danh-muc/{category}/sua', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('danh-muc/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('danh-muc/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('san-pham/manh', [AdminProductController::class, 'fragments'])->name('products.fragments');
    Route::get('san-pham', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('san-pham/them', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('san-pham', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('san-pham/{product}/sua', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('san-pham/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('san-pham/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    Route::get('don-hang', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('don-hang/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('don-hang/{order}/trang-thai', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    Route::get('nguoi-dung', [UserController::class, 'index'])->name('users.index');
    Route::get('nguoi-dung/them', [UserController::class, 'create'])->name('users.create');
    Route::post('nguoi-dung', [UserController::class, 'store'])->name('users.store');
    Route::get('nguoi-dung/{user}/sua', [UserController::class, 'edit'])->name('users.edit');
    Route::put('nguoi-dung/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('nguoi-dung/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

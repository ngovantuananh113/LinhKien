<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $request->user()->currentCart();
        $cart->load(['items.product.category']);

        return view('user.cart.index', compact('cart'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::query()->findOrFail($data['product_id']);
        $qty = min($data['quantity'], $product->quantity);

        if ($qty < 1) {
            return back()->with('error', 'Sản phẩm đã hết hàng.');
        }

        $cart = $request->user()->currentCart();
        $existing = $cart->items()->where('product_id', $product->id)->first();

        if ($existing) {
            $newQty = min($product->quantity, $existing->quantity + $qty);
            $existing->update(['quantity' => $newQty]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $qty,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã cập nhật giỏ hàng.');
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorizeCartItem($request, $cartItem);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = $cartItem->product;
        $qty = min($data['quantity'], $product->quantity);

        if ($qty < 1) {
            $cartItem->delete();

            return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ (hết hàng).');
        }

        $cartItem->update(['quantity' => $qty]);

        return redirect()->route('cart.index')->with('success', 'Đã cập nhật số lượng.');
    }

    public function destroy(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorizeCartItem($request, $cartItem);
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    private function authorizeCartItem(Request $request, CartItem $cartItem): void
    {
        abort_unless(
            $cartItem->cart->user_id === $request->user()->id,
            403
        );
    }
}

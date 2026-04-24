<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->user()->orders()->latest();

        if ($request->filled('q')) {
            $raw = trim((string) $request->input('q'));
            if ($raw !== '' && ctype_digit($raw)) {
                $query->where('id', (int) $raw);
            }
        }

        if ($request->filled('status') && in_array($request->input('status'), [
            Order::STATUS_PENDING,
            Order::STATUS_PROCESSING,
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELLED,
        ], true)) {
            $query->where('status', $request->input('status'));
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('user.orders.index', compact('orders'));
    }

    public function create(Request $request): RedirectResponse
    {
        return redirect()->route('cart.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = $request->user()->currentCart();
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống.');
        }

        $validated = $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:2000'],
            'city' => ['required', 'string', 'max:120'],
            'postal_code' => ['required', 'string', 'max:32'],
        ]);

        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->quantity) {
                return back()->withInput()->with('error', 'Số lượng "'.$item->product->name.'" vượt tồn kho.');
            }
        }

        $order = DB::transaction(function () use ($request, $cart, $validated) {
            $total = $cart->items->sum(fn ($item) => $item->product->price * $item->quantity);

            /** @var \App\Models\Order $order */
            $order = Order::query()->create([
                'user_id' => $request->user()->id,
                'recipient_name' => $validated['recipient_name'],
                'total_price' => $total,
                'status' => Order::STATUS_PENDING,
                'address' => $validated['address'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'],
                'phone' => $validated['phone'],
            ]);

            foreach ($cart->items as $item) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $item->product->decrement('quantity', $item->quantity);
            }

            $cart->items()->delete();

            return $order;
        });

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Đặt hàng thành công.');
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $order->load('items.product');

        return view('user.orders.show', compact('order'));
    }
}

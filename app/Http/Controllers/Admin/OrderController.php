<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function index(Request $request): View|StreamedResponse
    {
        if ($request->query('export') === 'csv') {
            return $this->exportCsv($request);
        }

        $base = Order::query()->with('user');

        $q = $request->string('q')->trim();
        if ($q !== '') {
            $base->where(function ($query) use ($q) {
                if (is_numeric($q)) {
                    $query->where('id', (int) $q);
                }
                $query->orWhere('recipient_name', 'like', '%'.$q.'%')
                    ->orWhere('phone', 'like', '%'.$q.'%')
                    ->orWhereHas('user', function ($uq) use ($q) {
                        $uq->where('name', 'like', '%'.$q.'%')
                            ->orWhere('email', 'like', '%'.$q.'%');
                    });
            });
        }

        $status = $request->string('status')->trim();
        if ($status !== '' && in_array($status, [
            Order::STATUS_PENDING,
            Order::STATUS_PROCESSING,
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELLED,
        ], true)) {
            $base->where('status', $status);
        }

        $orders = (clone $base)->latest()->paginate(15)->withQueryString();

        $totalVolumeUnits = (int) OrderItem::query()->sum('quantity');
        $activeTransfers = Order::query()
            ->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_PROCESSING])
            ->count();

        return view('admin.orders.index', compact('orders', 'totalVolumeUnits', 'activeTransfers'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,processing,completed,cancelled'],
        ]);

        $newStatus = $data['status'];
        $oldStatus = $order->status;

        if ($newStatus === Order::STATUS_CANCELLED && $oldStatus !== Order::STATUS_CANCELLED) {
            DB::transaction(function () use ($order, $newStatus) {
                $order->load('items.product');
                foreach ($order->items as $item) {
                    $item->product->increment('quantity', $item->quantity);
                }
                $order->update(['status' => $newStatus]);
            });
        } else {
            $order->update(['status' => $newStatus]);
        }

        if ($request->input('return_to') === 'index') {
            return redirect()->route('admin.orders.index', array_filter([
                'q' => $request->input('return_q'),
                'status' => $request->input('filter_status'),
                'page' => $request->input('return_page'),
            ], fn ($v) => $v !== null && $v !== ''))->with('success', 'Đã cập nhật trạng thái đơn hàng.');
        }

        return redirect()->route('admin.orders.show', $order)->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }

    private function exportCsv(Request $request): StreamedResponse
    {
        $query = Order::query()->with('user')->latest();

        $q = $request->string('q')->trim();
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                if (is_numeric($q)) {
                    $sub->where('id', (int) $q);
                }
                $sub->orWhere('recipient_name', 'like', '%'.$q.'%')
                    ->orWhere('phone', 'like', '%'.$q.'%')
                    ->orWhereHas('user', function ($uq) use ($q) {
                        $uq->where('name', 'like', '%'.$q.'%')
                            ->orWhere('email', 'like', '%'.$q.'%');
                    });
            });
        }

        $status = $request->string('status')->trim();
        if ($status !== '' && in_array($status, [
            Order::STATUS_PENDING,
            Order::STATUS_PROCESSING,
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELLED,
        ], true)) {
            $query->where('status', $status);
        }

        $filename = 'don_hang_'.now()->format('Y-m-d_His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['ID', 'Khách hàng', 'Email', 'Tổng (đ)', 'Trạng thái', 'Ngày tạo']);

            $query->chunk(100, function ($orders) use ($handle) {
                foreach ($orders as $order) {
                    fputcsv($handle, [
                        $order->id,
                        $order->user?->name ?? $order->recipient_name,
                        $order->user?->email ?? '',
                        $order->total_price,
                        $order->status,
                        $order->created_at?->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}

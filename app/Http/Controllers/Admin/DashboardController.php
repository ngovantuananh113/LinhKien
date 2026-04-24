<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'products' => Product::query()->count(),
            'orders' => Order::query()->count(),
            'users' => User::query()->where('role', 'user')->count(),
        ];

        $recentOrders = Order::query()
            ->with(['user:id,name,email'])
            ->latest()
            ->take(6)
            ->get();

        $productsPct = $stats['products'] > 0 ? min(100, (int) round(log($stats['products'] + 1, 10) * 40 + 20)) : 0;
        $ordersPct = $stats['orders'] > 0 ? min(100, (int) round(log($stats['orders'] + 1, 10) * 45 + 15)) : 0;
        $usersPct = $stats['users'] > 0 ? min(100, (int) round(log($stats['users'] + 1, 10) * 50 + 25)) : 0;

        $ordersByDay = [];
        $revenueByDay = [];
        $chartDayLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->startOfDay();
            $chartDayLabels[] = $day->format('d/m');
            $ordersByDay[] = (int) Order::query()->whereDate('created_at', $day->toDateString())->count();
            $revenueByDay[] = (float) Order::query()->whereDate('created_at', $day->toDateString())->sum('total_price');
        }

        $chartPathOrders = $this->buildSvgLinePath($ordersByDay);
        $chartPathRevenue = $this->buildSvgLinePath($revenueByDay);

        $maxDaily = max(max($ordersByDay), 1);
        $todayCount = (int) Order::query()->whereDate('created_at', today())->count();
        $cpuPct = min(100, (int) round($todayCount / $maxDaily * 100));

        $totalProducts = max($stats['products'], 1);
        $lowStock = Product::query()->where('quantity', '<', 10)->count();
        $memPct = min(100, (int) round($lowStock / $totalProducts * 100));

        $totalOrders = max($stats['orders'], 1);
        $completedOrders = Order::query()->where('status', Order::STATUS_COMPLETED)->count();
        $netPct = min(100, (int) round($completedOrders / $totalOrders * 100));

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'productsPct',
            'ordersPct',
            'usersPct',
            'chartPathOrders',
            'chartPathRevenue',
            'chartDayLabels',
            'cpuPct',
            'memPct',
            'netPct',
        ));
    }

    /**
     * @param  array<int, float|int>  $values
     */
    private function buildSvgLinePath(array $values, float $width = 1000, float $height = 400): string
    {
        $n = count($values);
        if ($n === 0) {
            return 'M 0 '.$height.' L '.$width.' '.$height;
        }

        $max = max(max($values), 0.000001);
        $padBottom = 45.0;
        $padTop = 25.0;
        $usable = $height - $padBottom - $padTop;

        $path = '';
        foreach ($values as $i => $v) {
            $x = $n === 1 ? 0.0 : ($i / ($n - 1)) * $width;
            $y = $height - $padBottom - ((float) $v / $max) * $usable;
            $path .= ($i === 0 ? 'M ' : ' L ').round($x, 1).' '.round($y, 1);
        }

        return trim($path);
    }
}

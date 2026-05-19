<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderSummaryResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function summary(Request $request): JsonResponse
    {
        $cacheKey = 'dashboard_summary';
        $fromCache = Cache::has($cacheKey);
        $data = Cache::remember($cacheKey, 300, function () use ($request) {
            $totalRevenue = Order::where('status', 'completed')->sum('total_price');
            $totalOrdersCompleted = Order::where('status', 'completed')->count();
            $totalOrdersToday = Order::whereDate('created_at', today())->count();
            $totalProductsActive = Product::where('is_active', true)->count();
            $lowStockCount = Product::where('stock', '<', 5)->count();

            $topProducts = Order::selectRaw('product_id, SUM(qty) as total_qty, SUM(total_price) as total_revenue')
                ->where('status', 'completed')
                ->groupBy('product_id')
                ->orderByDesc('total_qty')
                ->with(['product.category'])
                ->limit(5)
                ->get()
                ->map(function (Order $order) {
                    return [
                        'product_id' => $order->product_id,
                        'total_qty' => (int) $order->total_qty,
                        'total_revenue' => (float) $order->total_revenue,
                        'product' => $order->product ? [
                            'id' => $order->product->id,
                            'name' => $order->product->name,
                            'category' => $order->product->category ? [
                                'id' => $order->product->category->id,
                                'name' => $order->product->category->name,
                                'slug' => $order->product->category->slug,
                            ] : null,
                        ] : null,
                    ];
                })
                ->values();

            $latestOrders = Order::with(['product.category', 'user'])
                ->latest()
                ->limit(10)
                ->get();

            return [
                'stats' => [
                    'total_revenue' => (float) $totalRevenue,
                    'total_orders_completed' => $totalOrdersCompleted,
                    'total_orders_today' => $totalOrdersToday,
                    'total_products_active' => $totalProductsActive,
                    'low_stock_count' => $lowStockCount,
                ],
                'top_products' => $topProducts,
                'latest_orders' => OrderSummaryResource::collection($latestOrders)->toArray($request),
            ];
        });

        return response()->json(array_merge($data, [
            'from_cache' => $fromCache,
        ]));
    }

    public function clearCache(): JsonResponse
    {
        Cache::forget('dashboard_summary');

        return response()->json([
            'message' => 'Cache dashboard berhasil dibersihkan.',
        ]);
    }
}

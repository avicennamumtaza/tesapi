<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderSummaryResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request): Response
    {
        $order = DB::transaction(function () use ($request) {
            $product = Product::query()
                ->lockForUpdate()
                ->findOrFail($request->validated('product_id'));

            $qty = (int) $request->validated('qty');
            if ($product->stock < $qty) {
                return null;
            }

            $totalPrice = $qty * (float) $product->price;

            $product->decrement('stock', $qty);

            return Order::create([
                'user_id' => optional($request->user())->id,
                'product_id' => $product->id,
                'qty' => $qty,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);
        });

        if (! $order) {
            return response([
                'message' => 'Stok tidak cukup.',
            ], 422);
        }

        return response($order, 201);
    }

    public function show(User $user, Order $order): OrderSummaryResource
    {
        return new OrderSummaryResource($order->load(['product.category', 'user']));
    }
}

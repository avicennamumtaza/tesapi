<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->get();
        if ($users->isEmpty()) {
            return;
        }

        $statuses = ['pending', 'completed', 'cancelled'];

        for ($i = 0; $i < 24; $i++) {
            $productId = Product::query()
                ->where('is_active', true)
                ->where('stock', '>', 0)
                ->inRandomOrder()
                ->value('id');

            if (! $productId) {
                break;
            }

            DB::transaction(function () use ($productId, $users, $statuses) {
                $product = Product::query()->lockForUpdate()->find($productId);
                if (! $product || $product->stock < 1) {
                    return;
                }

                $qty = min($product->stock, random_int(1, 3));
                $totalPrice = $qty * (float) $product->price;

                $product->decrement('stock', $qty);

                Order::create([
                    'user_id' => $users->random()->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'total_price' => $totalPrice,
                    'status' => $statuses[array_rand($statuses)],
                ]);
            });
        }
    }
}

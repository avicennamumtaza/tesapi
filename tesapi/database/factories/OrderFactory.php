<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $qty = fake()->numberBetween(1, 4);

        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'qty' => $qty,
            'total_price' => 0,
            'status' => fake()->randomElement(['pending', 'completed', 'cancelled']),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Order $order) {
            $product = $order->product;
            if (! $product) {
                return;
            }

            $order->update([
                'total_price' => $order->qty * (float) $product->price,
            ]);
        });
    }
}

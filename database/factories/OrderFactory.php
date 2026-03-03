<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 10, 200);
        $deliveryFee = 5.00;

        return [
            'user_id' => User::factory()->client(),
            'shop_id' => User::factory()->shop(),
            'address_id' => Address::factory(),
            'order_number' => 'ORD-' . strtoupper(substr(uniqid(), -8)),
            'status' => 'pending',
            'payment_method' => fake()->randomElement(['cash', 'credit_card', 'debit_card', 'pix']),
            'payment_status' => 'pending',
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'discount' => 0,
            'total' => $subtotal + $deliveryFee,
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Order with confirmed status.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Order with preparing status.
     */
    public function preparing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'preparing',
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Order with delivered status.
     */
    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'delivered',
            'confirmed_at' => now()->subHour(),
            'delivered_at' => now(),
            'payment_status' => 'paid',
        ]);
    }

    /**
     * Order with cancelled status.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }

    /**
     * Order with paid payment status.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'paid',
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'user_id' => User::factory()->shop(),
            'category_id' => Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'image' => null,
            'price' => fake()->randomFloat(2, 5, 100),
            'promotional_price' => null,
            'stock' => fake()->numberBetween(1, 50),
            'is_active' => true,
            'is_featured' => false,
        ];
    }

    /**
     * Product with promotional price.
     */
    public function onSale(): static
    {
        return $this->state(function (array $attributes) {
            $price = $attributes['price'] ?? 50.00;
            return [
                'promotional_price' => round($price * 0.8, 2),
            ];
        });
    }

    /**
     * Out of stock product.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    /**
     * Inactive product.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Featured product.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}

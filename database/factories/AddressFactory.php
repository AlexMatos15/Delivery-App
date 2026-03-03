<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->client(),
            'label' => fake()->randomElement(['Casa', 'Trabalho', 'Outro']),
            'street' => fake()->streetName(),
            'number' => (string) fake()->numberBetween(1, 9999),
            'complement' => fake()->optional()->secondaryAddress(),
            'neighborhood' => fake()->citySuffix() . ' ' . fake()->lastName(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'zip_code' => fake()->numerify('#####-###'),
            'reference' => fake()->optional()->sentence(3),
            'is_default' => false,
        ];
    }

    /**
     * Set as default address.
     */
    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }
}

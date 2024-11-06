<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'status' => fake()->randomElement(['open', 'closed']),
            'store_type_id' => fake()->randomElement([1, 2, 3]),
            'max_delivery_distance' => fake()->randomFloat(2, 0, 100)
        ];
    }
}

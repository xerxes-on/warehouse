<?php

namespace Database\Factories;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'total_price' => $this->faker->randomFloat(2, 10, 1500),
            'status' => $this->faker->numberBetween(0, 3),
            'user_id' => User::factory(),
            'shipment_id' => Shipment::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

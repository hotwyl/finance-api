<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'qtd_users' => $this->faker->randomNumber(2),
            'qtd_wallets' => $this->faker->randomNumber(2),
            'qtd_transactions' => $this->faker->randomNumber(2),
            'description' => $this->faker->text,
        ];
    }
}

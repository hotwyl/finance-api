<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wallet_id' => function () {
                return \App\Models\Wallet::query()->inRandomOrder()->first()->id;
            },
            'type' => $this->faker->randomElement(['entrada', 'saida']),
            'amount' => $this->faker->randomFloat(2, 0, 10000),
            'status' => $this->faker->randomElement(['pendente', 'pago']),
            'annotation' => $this->faker->sentence,
            'due_date' => $this->faker->dateTimeBetween('-6 months', '+1 months')->format('Y-m-d'),
        ];
    }
}

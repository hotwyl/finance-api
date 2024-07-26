<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::factory()->create([
            'name' => 'Basic',
            'price' => 0,
            'qtd_users' => 1,
            'qtd_wallets' => 1,
            'qtd_transactions' => 25,
        ]);

        Plan::factory()->create([
            'name' => 'Pro',
            'price' => 4.99,
            'qtd_users' => 1,
            'qtd_wallets' => 3,
            'qtd_transactions' => 50,
        ]);

        Plan::factory()->create([
            'name' => 'Premium',
            'price' => 9.99,
            'qtd_users' => 1,
            'qtd_wallets' => 5,
            'qtd_transactions' => 100,
        ]);

        Plan::factory()->create([
            'name' => 'Plus',
            'price' => 14.99,
            'qtd_users' => 1,
            'qtd_wallets' => 7,
            'qtd_transactions' => 250,
        ]);

        Plan::factory()->create([
            'name' => 'Ultimate',
            'price' => 19.99,
            'qtd_users' => 1,
            'qtd_wallets' => 10,
            'qtd_transactions' => 500,
        ]);
    }
}

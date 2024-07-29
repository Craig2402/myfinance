<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // $startDate = now()->subMonths(2)->startOfDay();
        // $endDate = now()->addMonth()->endOfDay();

        // $reasons = ['transport', 'snacks', 'lunch'];

        // while ($startDate <= $endDate) {
        //     for ($i = 0; $i < 5; $i++) {
        //         \App\Models\transactionsModel::create([
        //             'transaction_id' => uniqid(),
        //             'amount' => rand(100, 10000) / 100,
        //             'date' => $startDate,
        //             'reason' => $reasons[array_rand($reasons)],
        //         ]);
        //     }
        //     $startDate->addDay();
        // }

    }
}

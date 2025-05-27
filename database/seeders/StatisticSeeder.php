<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Statistic;
use App\Models\Novel;

class StatisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Statistic::create([
            'views' => 120,
            'likes' => 45,
            'shares' => 10,
            'novel_id' => 1,
        ]);

        Statistic::create([
            'views' => 300,
            'likes' => 120,
            'shares' => 25,
            'novel_id' => 2,
        ]);

        Statistic::create([
            'views' => 75,
            'likes' => 20,
            'shares' => 5,
            'novel_id' => 3,
        ]);
    }
}

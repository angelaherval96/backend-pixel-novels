<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reading;
use App\Models\Chapter;
use App\Models\User;


class ReadingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reading::create([
            'progress' => 1,
            'user_id' => 1,
            'chapter_id' => 1,
            'read_at' => now(),
        ]);

        Reading::create([
            'progress' => 0,
            'user_id' => 2,
            'chapter_id' => 2,
            'read_at' => now()->subDay(),
        ]);

        Reading::create([
            'progress' => 1,
            'user_id' => 1,
            'chapter_id' => 3,
            'read_at' => now()->subDays(2),
        ]);
    }
}

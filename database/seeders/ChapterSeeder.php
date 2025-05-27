<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Chapter;
use App\Models\Novel;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Chapter::create([
            'title' => 'Capítulo 1: El comienzo',
            'content' => 'Contenido del primer capítulo de la novela.',
            'novel_id' => 1,
        ]);

        Chapter::create([
            'title' => 'Capítulo 2: El misterio',
            'content' => 'Contenido del segundo capítulo de la novela.',
            'novel_id' => 1,
        ]);

        Chapter::create([
            'title' => 'Capítulo 1: Macondo',
            'content' => 'Primer capítulo de Cien Años de Soledad.',
            'novel_id' => 2,
        ]);
    }
}

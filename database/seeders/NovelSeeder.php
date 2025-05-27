<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Novel;
use App\Models\User;

class NovelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Novel::create([
            'title' => 'La Sombra del Viento',
            'description' => 'Una novela de misterio y aventuras en la Barcelona de posguerra.',
            'language' => 'es',
            'cover' => 'https://ejemplo.com/covers/sombra.jpg',
            'user_id' => 1,
        ]);

        Novel::create([
            'title' => 'Cien Años de Soledad',
            'description' => 'La historia de la familia Buendía en Macondo.',
            'language' => 'es',
            'cover' => 'https://ejemplo.com/covers/cien.jpg',
            'user_id' => 2,
        ]);

        Novel::create([
            'title' => 'El Juego del Ángel',
            'description' => null,
            'language' => 'es',
            'cover' => 'https://ejemplo.com/covers/angel.jpg',
            'user_id' => 1,
        ]);
    }
}

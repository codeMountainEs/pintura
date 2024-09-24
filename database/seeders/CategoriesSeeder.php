<?php

namespace Database\Seeders;

use App\Models\category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marcas = [
            'Categoria 1',
            'categoria 2',


        ];

        foreach ($marcas as $marca) {
            category::create(['name' => $marca,'slug' => $marca]);
        }
    }
}

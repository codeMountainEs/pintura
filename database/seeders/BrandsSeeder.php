<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marcas = [
            'marca 1',
            'marca 2',


        ];

        foreach ($marcas as $marca) {
            brand::create(['name' => $marca, 'slug' => $marca]);
        }
    }
}

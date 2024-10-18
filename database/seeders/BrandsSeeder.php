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
            'INTERPON',



        ];

        foreach ($marcas as $marca) {
            Brand::create(['name' => $marca, 'slug' => $marca]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            'producto  1',
            'producto  2',


        ];

        foreach ($productos as $producto) {
            product::create([
                'name' => $producto,
                'slug' => $producto,
                'category_id' => 1,
                'brand_id' => 1,
                'rendimiento' => 10,
                'description' => "Marca " . $producto,
                'medida' => 'Litros',

            ]);
        }





    }
}

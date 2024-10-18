<?php

namespace Database\Seeders;

use App\Models\Product;
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
            'RAL 6003 TEXTURADO',
            'RAL 9005 TEXTURADO',
            'RAL 9003 MATE',
            'RAL 7016 TEXTURADO',
            'RAL 6018 SATINADO',
        ];

        foreach ($productos as $producto) {
            Product::create([
                'name' => $producto,
                'slug' => $producto,
                'category_id' => 1,
                'brand_id' => 1,
                'rendimiento' => 10,
                'rendimiento2' => 5,
                'description' => "Marca " . $producto,
                'medida' => 'Kg',

            ]);
        }

     /*   protected $fillable = ['category_id', 'brand_id', 'name',
        'slug', 'images', 'rendimiento', 'rendimiento2',
        'description', 'price', 'is_active', 'is_featured',
        'in_stock', 'on_sale', 'medida'];*/






    }
}

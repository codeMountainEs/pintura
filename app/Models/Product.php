<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id','brand_id','name','slug','images','rendimiento',
        'description','price','is_active','is_featured','in_stock','on_sale','medida'];



    protected $casts = ['images' => 'array'];


    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function movimientos(){
        return $this->hasMany(Movimiento::class);
    }

    public function stock()
    {
        $stock = $this->movimientos()
                ->where('tipo', 'Compras')
                ->sum('cantidad')
            - $this->movimientos()
                ->where('tipo', 'Ventas')
                ->sum('cantidad');
        return round($stock,2);
    }
}

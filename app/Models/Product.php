<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id','brand_id','name','slug','images','rendimiento',
        'description','price','is_active','is_featured','in_stock','on_sale','medida'];



    protected $casts = ['images' => 'array'];


    public function category(){
        return $this->belongsTo(category::class);
    }

    public function brand(){
        return $this->belongsTo(brand::class);
    }

    public function movimientos(){
        return $this->hasMany(movimiento::class);
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

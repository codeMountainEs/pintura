<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'user_id', 'origen','descripcion','tipo',
        'capas', 'rendimiento', 'rendimiento2', 'superficie','cantidad', 'medida','precio','total','destino'
        ,'unidades', 'rendimiento_tipo'
    ];


    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function salidas()
    {
       return $this->movimientos()
                ->where('tipo', 'Salidas')
                ->count();

    }

    public function entradas()
    {
        return $this->movimientos()
            ->where('tipo', 'Entradas')
            ->count();

    }


}

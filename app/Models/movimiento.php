<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class movimiento extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'user_id', 'origen','descripcion','tipo',
        'capas', 'rendimiento', 'superficie','cantidad', 'medida','precio','total','destino'
    ];


    public function product(){
        return $this->belongsTo(product::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function ventas()
    {
       return $this->movimientos()
                ->where('tipo', 'Ventas')
                ->count();

    }

    public function compras()
    {
        return $this->movimientos()
            ->where('tipo', 'Compras')
            ->count();

    }
}

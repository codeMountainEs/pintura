<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acceso extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','fecha','hora','fecha_real','hora_real','descripcion','observaciones','condicion',
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }
}

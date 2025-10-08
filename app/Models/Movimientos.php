<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimientos extends Model
{
    protected $fillable = [
        'id_movimiento',
        'tipo_movimiento',
        'cantidad',
        'fecha_movimiento',
        'observaciones',
        'id_producto',
        'id_usuario',
    ];
    protected $primaryKey = 'id_movimiento';
    public $incrementing = false;
    protected $keyType = 'string';

}

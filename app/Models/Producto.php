<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'id_producto',
        'codigo_producto',
        'nombre_producto',
        'stock_minimo',
        'stock_actual',
        'observaciones',
        'id_unidad',
    ];
    protected $primaryKey = 'id_producto';
    public $incrementing = false;
    protected $keyType = 'string';
}

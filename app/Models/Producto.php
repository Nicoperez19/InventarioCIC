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

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'id_unidad', 'id_unidad');
    }

    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'id_producto', 'id_producto');
    }
}

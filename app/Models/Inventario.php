<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $fillable = [
        'id_inventario',
        'id_producto',
        'fecha_inventario',
        'cantidad_inventario',
    ];

    protected $primaryKey = 'id_inventario';
    public $incrementing = false;
    protected $keyType = 'string';

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}

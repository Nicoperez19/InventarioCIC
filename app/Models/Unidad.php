<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    protected $fillable = [
        'id_unidad',
        'nombre_unidad',
    ];

    protected $primaryKey = 'id_unidad';
    public $incrementing = false;
    protected $keyType = 'string';

    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_unidad', 'id_unidad');
    }
}

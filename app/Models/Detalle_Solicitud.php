<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalle_Solicitud extends Model
{
    protected $fillable = [
        'id_detalle_solicitud',
        'id_solicitud',
        'id_producto',
        'cantidad_solicitud',
    ];
    protected $primaryKey = 'id_detalle_solicitud';
    public $incrementing = false;
    protected $keyType = 'string';
}

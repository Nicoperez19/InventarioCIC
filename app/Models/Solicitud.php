<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $fillable = [
        'id_solicitud',
        'fecha_solicitud',
        'estado_solicitud',
        'observaciones',
        'id_usuario',
    ];
    protected $primaryKey = 'id_solicitud';
    public $incrementing = false;
    protected $keyType = 'string';
}

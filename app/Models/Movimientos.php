<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimientos extends Model
{
    protected $fillable = [
        'id_depto',
        'nombre_depto',
    ];
    protected $primaryKey = 'id_depto';
    public $incrementing = false;
    protected $keyType = 'string';

}

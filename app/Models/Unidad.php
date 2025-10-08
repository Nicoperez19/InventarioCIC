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
}

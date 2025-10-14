<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $fillable = [
        'id_depto',
        'nombre_depto',
    ];

    protected $primaryKey = 'id_depto';
    public $incrementing = false;
    protected $keyType = 'string';

    public function users()
    {
        return $this->hasMany(User::class, 'id_depto', 'id_depto');
    }
}

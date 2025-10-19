<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $primaryKey = 'run';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'run',
        'nombre',
        'correo',
        'contrasena',
        'id_depto',
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'correo_verificado_at' => 'datetime',
            'contrasena' => 'hashed',
        ];
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_depto', 'id_depto');
    }

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function getAuthIdentifierName()
    {
        return 'run';
    }

    public function getAuthIdentifier()
    {
        return $this->run;
    }

    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }

    public function getEmailForVerification()
    {
        return $this->correo;
    }

    public function getNameAttribute()
    {
        return $this->nombre;
    }

    public function getEmailAttribute()
    {
        return $this->correo;
    }

    public function getPasswordAttribute()
    {
        return $this->contrasena;
    }

    public static function findByEmail($email)
    {
        return static::where('correo', $email)->first();
    }

    public static function findByEmailOrFail($email)
    {
        return static::where('correo', $email)->firstOrFail();
    }
}

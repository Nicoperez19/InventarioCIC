<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $primaryKey = 'run';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'run',
        'nombre',
        'correo',
        'contrasena',
        'id_depto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'correo_verificado_at' => 'datetime',
            'contrasena' => 'hashed',
        ];
    }

    /**
     * Relación con Departamento
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_depto', 'id_depto');
    }

    /**
     * Get the password for the user.
     * Compatibilidad con Laravel Auth
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    /**
     * Get the name of the unique identifier for the user.
     * Compatibilidad con Laravel Auth
     */
    public function getAuthIdentifierName()
    {
        return 'run';
    }

    /**
     * Get the unique identifier for the user.
     * Compatibilidad con Laravel Auth
     */
    public function getAuthIdentifier()
    {
        return $this->run;
    }

    /**
     * Get the email address for the user.
     * Compatibilidad con Spatie Permission
     */
    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }

    /**
     * Get the email address for the user.
     * Compatibilidad con Spatie Permission
     */
    public function getEmailForVerification()
    {
        return $this->correo;
    }

    /**
     * Get the name for the user.
     * Compatibilidad con Spatie Permission
     */
    public function getNameAttribute()
    {
        return $this->nombre;
    }

    /**
     * Get the email for the user.
     * Compatibilidad con Spatie Permission
     */
    public function getEmailAttribute()
    {
        return $this->correo;
    }

    /**
     * Get the password for the user.
     * Compatibilidad con Spatie Permission
     */
    public function getPasswordAttribute()
    {
        return $this->contrasena;
    }
}

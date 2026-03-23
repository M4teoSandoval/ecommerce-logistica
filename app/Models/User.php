<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'administrador';
    }

    public function isProveedor(): bool
    {
        return $this->role === 'proveedor';
    }

    public function isCliente(): bool
    {
        return $this->role === 'cliente';
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}

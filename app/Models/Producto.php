<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'user_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'peso',
        'alto',
        'ancho',
        'largo',
        'categoria',
        'imagen',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'precio' => 'decimal:2',
    ];

    public function proveedor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getPrecioFormateadoAttribute(): string
    {
        return '$' . number_format($this->precio, 0, ',', '.');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeDeProveedor($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
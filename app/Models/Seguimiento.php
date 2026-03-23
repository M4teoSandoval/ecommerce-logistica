<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    protected $fillable = [
        'pedido_id',
        'estado',
        'descripcion',
        'latitud',
        'longitud',
        'ubicacion_descripcion',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function getIconoAttribute(): string
    {
        return match($this->estado) {
            'pendiente'  => 'bi-clock',
            'confirmado' => 'bi-check-circle',
            'preparando' => 'bi-box-seam',
            'en_camino'  => 'bi-truck',
            'cerca'      => 'bi-geo-alt',
            'entregado'  => 'bi-bag-check',
            'cancelado'  => 'bi-x-circle',
            default      => 'bi-circle',
        };
    }

    public function getColorAttribute(): string
    {
        return match($this->estado) {
            'pendiente'  => '#94a3b8',
            'confirmado' => '#6366f1',
            'preparando' => '#f59e0b',
            'en_camino'  => '#3b82f6',
            'cerca'      => '#8b5cf6',
            'entregado'  => '#16a34a',
            'cancelado'  => '#ef4444',
            default      => '#94a3b8',
        };
    }
}
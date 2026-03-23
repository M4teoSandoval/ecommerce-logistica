<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'user_id',
        'direccion_entrega',
        'ciudad',
        'telefono',
        'transporte',
        'estado',
        'subtotal',
        'costo_envio',
        'total',
        'notas',
    ];

    public function cliente()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function getTotalFormateadoAttribute(): string
    {
        return '$' . number_format($this->total, 0, ',', '.');
    }

    public function getEstadoColorAttribute(): string
    {
        return match($this->estado) {
            'pendiente'  => 'background:#f1f5f9;color:#64748b',
            'confirmado' => 'background:#dbeafe;color:#1d4ed8',
            'en_camino'  => 'background:#fef9c3;color:#854d0e',
            'entregado'  => 'background:#dcfce7;color:#16a34a',
            'cancelado'  => 'background:#fee2e2;color:#dc2626',
            default      => 'background:#f1f5f9;color:#64748b',
        };
    }

    public function getTransporteIconAttribute(): string
    {
        return match($this->transporte) {
            'dron'      => 'bi-send',
            'moto'      => 'bi-bicycle',
            'furgoneta' => 'bi-truck',
            default     => 'bi-truck',
        };
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'user_id',
        'repartidor_id',
        'stripe_session_id',
        'stripe_payment_status',
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

    public function repartidor()
    {
        return $this->belongsTo(User::class, 'repartidor_id');
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
        return match ($this->estado) {
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
        return match ($this->transporte) {
            'dron'      => 'bi-send',
            'moto'      => 'bi-bicycle',
            'furgoneta' => 'bi-truck',
            default     => 'bi-truck',
        };
    }
    public function seguimientos()
    {
        return $this->hasMany(Seguimiento::class)->orderBy('created_at', 'asc');
    }

    public function ultimoSeguimiento()
    {
        return $this->hasOne(Seguimiento::class)->latestOfMany();
    }

    public function scopeDeProveedor($query, $proveedorId)
    {
        return $query->whereHas('items.producto', function ($q) use ($proveedorId) {
            $q->where('user_id', $proveedorId);
        });
    }

    public function itemsDeProveedor($proveedorId)
    {
        return $this->items()->whereHas('producto', function ($q) use ($proveedorId) {
            $q->where('user_id', $proveedorId);
        })->with('producto');
    }

    public function tieneProductosDeProveedor($proveedorId): bool
    {
        return $this->items()->whereHas('producto', function ($q) use ($proveedorId) {
            $q->where('user_id', $proveedorId);
        })->exists();
    }

    public function scopeAsignadoA($query, $repartidorId)
    {
        return $query->where('repartidor_id', $repartidorId);
    }
}

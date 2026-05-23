<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = [
        'pedido_id',
        'user_id',
        'numero_factura',
        'subtotal',
        'iva',
        'total',
        'xml_generado',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function cliente()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTotalFormateadoAttribute(): string
    {
        return '$' . number_format($this->total, 0, ',', '.');
    }

    public function getSubtotalFormateadoAttribute(): string
    {
        return '$' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getIvaFormateadoAttribute(): string
    {
        return '$' . number_format($this->iva, 0, ',', '.');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    protected $fillable = [
        'drone_id',
        'tipo',
        'fecha',
        'descripcion',
        'costo',
        'tecnico',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function drone()
    {
        return $this->belongsTo(Drone::class);
    }

    public function getTipoColorAttribute(): string
    {
        return match($this->tipo) {
            'preventivo' => 'background:#dcfce7;color:#16a34a',
            'correctivo' => 'background:#fef9c3;color:#854d0e',
            'incidente'  => 'background:#fee2e2;color:#dc2626',
            default      => 'background:#f1f5f9;color:#64748b',
        };
    }
}
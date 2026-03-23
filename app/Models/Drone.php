<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drone extends Model
{
    protected $fillable = [
        'nombre',
        'modelo',
        'peso_maximo',
        'distancia_maxima',
        'altitud_maxima',
        'velocidad_promedio',
        'capacidad_bateria',
        'consumo_por_km',
        'estado',
        'horas_vuelo_total',
        'horas_vuelo_desde_mantenimiento',
        'horas_alerta_mantenimiento',
        'zonas_prohibidas',
        'notas',
    ];

    public function mantenimientos()
    {
        return $this->hasMany(Mantenimiento::class);
    }

    public function getEstadoColorAttribute(): string
    {
        return match($this->estado) {
            'disponible'   => 'background:#dcfce7;color:#16a34a',
            'en_vuelo'     => 'background:#dbeafe;color:#1d4ed8',
            'mantenimiento'=> 'background:#fef9c3;color:#854d0e',
            'inactivo'     => 'background:#fee2e2;color:#dc2626',
            default        => 'background:#f1f5f9;color:#64748b',
        };
    }

    public function getNecesitaMantenimientoAttribute(): bool
    {
        return $this->horas_vuelo_desde_mantenimiento >= $this->horas_alerta_mantenimiento;
    }

    public function getPorcentajeBateriaAttribute(): float
    {
        if ($this->distancia_maxima <= 0) return 0;
        $consumoTotal = $this->consumo_por_km * $this->distancia_maxima;
        return min(100, ($this->capacidad_bateria / max($consumoTotal, 1)) * 100);
    }

    public function simularRuta(float $distanciaKm): array
    {
        $tiempoHoras    = $distanciaKm / $this->velocidad_promedio;
        $tiempoMinutos  = round($tiempoHoras * 60);
        $consumoBateria = $this->consumo_por_km * $distanciaKm;
        $porcentajeBateria = round(($consumoBateria / $this->capacidad_bateria) * 100, 1);
        $factible = $distanciaKm <= $this->distancia_maxima && $porcentajeBateria <= 100;

        return [
            'distancia_km'      => $distanciaKm,
            'tiempo_minutos'    => $tiempoMinutos,
            'consumo_mah'       => round($consumoBateria, 0),
            'porcentaje_bateria'=> $porcentajeBateria,
            'factible'          => $factible,
            'velocidad'         => $this->velocidad_promedio,
        ];
    }
}
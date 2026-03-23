@extends('layouts.app')
@section('title', 'Resultado Simulación')
@section('page-title', 'Resultado de Simulación')
@section('page-subtitle', 'Dron: {{ $drone->nombre }}')
@section('content')

<div class="row g-4">
    <div class="col-md-5">
        <div class="content-card">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:16px;">Parámetros usados</div>
            @foreach([
                ['Dron', $drone->nombre],
                ['Distancia', $resultado['distancia_km'].' km'],
                ['Peso de carga', $resultado['peso_carga'].' kg'],
                ['Velocidad', $resultado['velocidad'].' km/h'],
            ] as $p)
            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #f1f5f9;font-size:0.83rem;">
                <span style="color:#64748b;">{{ $p[0] }}</span>
                <span style="font-weight:600;color:#334155;">{{ $p[1] }}</span>
            </div>
            @endforeach
            <a href="{{ route('admin.simulacion.index') }}" class="btn w-100 mt-4" style="border-radius:10px;background:#f1f5f9;color:#475569;font-size:0.85rem;padding:9px;">
                <i class="bi bi-arrow-left me-2"></i>Nueva simulación
            </a>
        </div>
    </div>

    <div class="col-md-7">
        <div class="content-card">
            <div class="text-center mb-4">
                @if($resultado['factible'] && $resultado['peso_ok'])
                    <div class="stat-icon icon-green mx-auto mb-3" style="width:64px;height:64px;font-size:1.8rem;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div style="font-size:1.1rem;font-weight:700;color:#16a34a;">Entrega Factible</div>
                    <div style="font-size:0.82rem;color:#94a3b8;margin-top:4px;">El dron puede completar esta ruta exitosamente</div>
                @else
                    <div class="stat-icon icon-red mx-auto mb-3" style="width:64px;height:64px;font-size:1.8rem;">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div style="font-size:1.1rem;font-weight:700;color:#dc2626;">Entrega No Factible</div>
                    <div style="font-size:0.82rem;color:#94a3b8;margin-top:4px;">
                        @if(!$resultado['peso_ok']) El peso supera la capacidad del dron. @endif
                        @if(!$resultado['factible']) La distancia supera el alcance máximo. @endif
                    </div>
                @endif
            </div>

            <div class="row g-3 mb-4">
                @foreach([
                    ['Tiempo estimado', $resultado['tiempo_minutos'].' minutos', 'bi-clock', 'icon-purple'],
                    ['Consumo batería', $resultado['consumo_mah'].' mAh', 'bi-battery-half', 'icon-blue'],
                    ['% Batería usado', $resultado['porcentaje_bateria'].'%', 'bi-lightning', 'icon-orange'],
                    ['Peso OK', $resultado['peso_ok'] ? 'Sí' : 'No', 'bi-box-seam', $resultado['peso_ok'] ? 'icon-green' : 'icon-red'],
                ] as $r)
                <div class="col-6">
                    <div style="background:#f8fafc;border-radius:12px;padding:16px;text-align:center;">
                        <div class="stat-icon {{ $r[3] }} mx-auto mb-2" style="width:40px;height:40px;font-size:0.95rem;">
                            <i class="bi {{ $r[2] }}"></i>
                        </div>
                        <div style="font-size:1.1rem;font-weight:700;color:#0f172a;">{{ $r[1] }}</div>
                        <div style="font-size:0.72rem;color:#94a3b8;margin-top:2px;">{{ $r[0] }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <div style="margin-bottom:8px;">
                <div class="d-flex justify-content-between mb-2" style="font-size:0.78rem;color:#94a3b8;">
                    <span>Consumo de batería</span>
                    <span>{{ $resultado['porcentaje_bateria'] }}%</span>
                </div>
                <div style="background:#f1f5f9;border-radius:99px;height:10px;">
                    @php $pct = min(100, $resultado['porcentaje_bateria']) @endphp
                    <div style="background:{{ $pct > 90 ? '#ef4444' : ($pct > 70 ? '#f59e0b' : '#6366f1') }};width:{{ $pct }}%;height:10px;border-radius:99px;transition:width 0.5s;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
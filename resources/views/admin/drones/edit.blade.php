@extends('layouts.app')
@section('title', 'Editar Dron')
@section('page-title', 'Editar Dron')
@section('page-subtitle', $drone->nombre)
@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="content-card">
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li style="font-size:0.82rem;">{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.drones.update', $drone) }}">
                @csrf @method('PUT')

                <div style="font-size:0.8rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">Identificación</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $drone->nombre) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Modelo</label>
                        <input type="text" name="modelo" class="form-control" value="{{ old('modelo', $drone->modelo) }}">
                    </div>
                </div>

                <div style="font-size:0.8rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">Parámetros de vuelo</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Peso máximo (kg)</label>
                        <input type="number" name="peso_maximo" class="form-control" value="{{ old('peso_maximo', $drone->peso_maximo) }}" step="0.1" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Distancia máxima (km)</label>
                        <input type="number" name="distancia_maxima" class="form-control" value="{{ old('distancia_maxima', $drone->distancia_maxima) }}" step="0.1" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Altitud máxima (m)</label>
                        <input type="number" name="altitud_maxima" class="form-control" value="{{ old('altitud_maxima', $drone->altitud_maxima) }}" step="1" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Velocidad promedio (km/h)</label>
                        <input type="number" name="velocidad_promedio" class="form-control" value="{{ old('velocidad_promedio', $drone->velocidad_promedio) }}" step="1" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Capacidad batería (mAh)</label>
                        <input type="number" name="capacidad_bateria" class="form-control" value="{{ old('capacidad_bateria', $drone->capacidad_bateria) }}" step="100" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Consumo por km (mAh)</label>
                        <input type="number" name="consumo_por_km" class="form-control" value="{{ old('consumo_por_km', $drone->consumo_por_km) }}" step="10" min="0" required>
                    </div>
                </div>

                <div style="font-size:0.8rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">Estado y mantenimiento</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Estado actual</label>
                        <select name="estado" class="form-select" required>
                            @foreach(['disponible','en_vuelo','mantenimiento','inactivo'] as $e)
                                <option value="{{ $e }}" {{ old('estado', $drone->estado)==$e ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$e)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Alerta mantenimiento (horas)</label>
                        <input type="number" name="horas_alerta_mantenimiento" class="form-control" value="{{ old('horas_alerta_mantenimiento', $drone->horas_alerta_mantenimiento) }}" step="1" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Horas vuelo total</label>
                        <input type="number" name="horas_vuelo_total" class="form-control" value="{{ old('horas_vuelo_total', $drone->horas_vuelo_total) }}" step="0.1" min="0">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notas</label>
                        <textarea name="notas" class="form-control" rows="2">{{ old('notas', $drone->notas) }}</textarea>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('admin.drones.index') }}" class="btn" style="border-radius:10px;background:#f1f5f9;color:#475569;font-size:0.85rem;padding:9px 18px;">Cancelar</a>
                    <button type="submit" class="btn btn-primary" style="border-radius:10px;font-size:0.85rem;padding:9px 18px;">
                        <i class="bi bi-check-lg me-2"></i>Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
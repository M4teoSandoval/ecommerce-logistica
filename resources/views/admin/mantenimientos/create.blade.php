@extends('layouts.app')
@section('title', 'Registrar Mantenimiento')
@section('page-title', 'Registrar Mantenimiento')
@section('page-subtitle', 'Nuevo registro de mantenimiento')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="content-card">
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li style="font-size:0.82rem;">{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.mantenimientos.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Dron</label>
                        <select name="drone_id" class="form-select" required>
                            <option value="">Selecciona un dron...</option>
                            @foreach($drones as $d)
                                <option value="{{ $d->id }}" {{ (request('drone_id')==$d->id || old('drone_id')==$d->id) ? 'selected':'' }}>
                                    {{ $d->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipo de mantenimiento</label>
                        <select name="tipo" class="form-select" required>
                            <option value="">Selecciona...</option>
                            <option value="preventivo" {{ old('tipo')=='preventivo' ? 'selected':'' }}>Preventivo</option>
                            <option value="correctivo" {{ old('tipo')=='correctivo' ? 'selected':'' }}>Correctivo</option>
                            <option value="incidente"  {{ old('tipo')=='incidente'  ? 'selected':'' }}>Incidente</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="{{ old('fecha', now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select" required>
                            <option value="programado"  {{ old('estado')=='programado'  ? 'selected':'' }}>Programado</option>
                            <option value="en_proceso"  {{ old('estado')=='en_proceso'  ? 'selected':'' }}>En proceso</option>
                            <option value="completado"  {{ old('estado')=='completado'  ? 'selected':'' }}>Completado</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Técnico responsable</label>
                        <input type="text" name="tecnico" class="form-control" value="{{ old('tecnico') }}" placeholder="Nombre del técnico">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Costo (COP)</label>
                        <input type="number" name="costo" class="form-control" value="{{ old('costo') }}" placeholder="0" min="0" step="1000">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción del trabajo</label>
                        <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe el mantenimiento realizado..." required>{{ old('descripcion') }}</textarea>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('admin.mantenimientos.index') }}" class="btn" style="border-radius:10px;background:#f1f5f9;color:#475569;font-size:0.85rem;padding:9px 18px;">Cancelar</a>
                    <button type="submit" class="btn btn-primary" style="border-radius:10px;font-size:0.85rem;padding:9px 18px;">
                        <i class="bi bi-check-lg me-2"></i>Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
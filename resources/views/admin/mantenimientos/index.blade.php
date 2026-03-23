@extends('layouts.app')
@section('title', 'Mantenimientos')
@section('page-title', 'Mantenimientos')
@section('page-subtitle', 'Registro de mantenimientos de la flota')
@section('content')

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.mantenimientos.create') }}" class="btn btn-primary" style="border-radius:10px;font-size:0.85rem;padding:9px 18px;">
        <i class="bi bi-plus-lg me-2"></i>Registrar mantenimiento
    </a>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="table align-middle" style="font-size:0.83rem;">
            <thead>
                <tr style="color:#94a3b8;">
                    <th style="font-weight:500;border:none;padding-bottom:14px;">Dron</th>
                    <th style="font-weight:500;border:none;padding-bottom:14px;">Tipo</th>
                    <th style="font-weight:500;border:none;padding-bottom:14px;">Descripción</th>
                    <th style="font-weight:500;border:none;padding-bottom:14px;">Fecha</th>
                    <th style="font-weight:500;border:none;padding-bottom:14px;">Técnico</th>
                    <th style="font-weight:500;border:none;padding-bottom:14px;">Costo</th>
                    <th style="font-weight:500;border:none;padding-bottom:14px;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mantenimientos as $m)
                <tr style="border-color:#f8fafc;">
                    <td>
                        <div style="font-weight:600;color:#334155;">{{ $m->drone->nombre ?? 'N/A' }}</div>
                        <div style="font-size:0.72rem;color:#94a3b8;">{{ $m->drone->modelo ?? '' }}</div>
                    </td>
                    <td>
                        <span style="{{ $m->tipo_color }};padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:600;">
                            {{ ucfirst($m->tipo) }}
                        </span>
                    </td>
                    <td style="color:#475569;max-width:200px;">{{ Str::limit($m->descripcion, 50) }}</td>
                    <td style="color:#64748b;">{{ $m->fecha->format('d/m/Y') }}</td>
                    <td style="color:#64748b;">{{ $m->tecnico ?? '—' }}</td>
                    <td style="font-weight:600;color:#0f172a;">{{ $m->costo ? '$'.number_format($m->costo,0,',','.') : '—' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.mantenimientos.update', $m) }}">
                            @csrf @method('PATCH')
                            <select name="estado" class="form-select form-select-sm" style="font-size:0.75rem;border-radius:7px;width:120px;" onchange="this.form.submit()">
                                @foreach(['programado','en_proceso','completado'] as $e)
                                    <option value="{{ $e }}" {{ $m->estado == $e ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$e)) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4" style="color:#94a3b8;">Sin registros de mantenimiento</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $mantenimientos->links() }}</div>
</div>
@endsection
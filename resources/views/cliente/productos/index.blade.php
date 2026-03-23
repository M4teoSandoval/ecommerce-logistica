@extends('layouts.app')
@section('title', 'Productos')
@section('page-title', 'Catálogo de productos')
@section('page-subtitle', 'Encuentra lo que necesitas')
@section('content')

<div class="content-card mb-4">
    <form method="GET" action="{{ route('cliente.productos.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Buscar</label>
                <input type="text" name="buscar" class="form-control" placeholder="Buscar productos..." value="{{ request('buscar') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Categoría</label>
                <select name="categoria" class="form-select">
                    <option value="">Todas</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat }}" {{ request('categoria')==$cat ? 'selected':'' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Precio máximo</label>
                <input type="number" name="precio_max" class="form-control" placeholder="500000" value="{{ request('precio_max') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100" style="border-radius:10px;">
                    <i class="bi bi-search me-1"></i> Buscar
                </button>
            </div>
        </div>
    </form>
</div>

@if($productos->isEmpty())
    <div class="content-card text-center py-5">
        <div class="stat-icon icon-blue mx-auto mb-3" style="width:56px;height:56px;font-size:1.5rem;">
            <i class="bi bi-search"></i>
        </div>
        <div style="font-size:0.95rem;font-weight:600;color:#334155;">No se encontraron productos</div>
        <div style="font-size:0.8rem;color:#94a3b8;margin-top:4px;">Intenta con otros filtros</div>
    </div>
@else
    <div class="row g-3">
        @foreach($productos as $producto)
        <div class="col-md-4 col-lg-3">
            <div class="content-card p-0 overflow-hidden" style="transition:transform 0.2s,box-shadow 0.2s;"
                 onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.transform='';this.style.boxShadow=''">
                @if($producto->imagen)
                    <img src="{{ asset('storage/'.$producto->imagen) }}"
                         style="width:100%;height:160px;object-fit:cover;">
                @else
                    <div style="width:100%;height:160px;background:linear-gradient(135deg,#ede9fe,#dbeafe);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-box" style="font-size:2.5rem;color:#94a3b8;"></i>
                    </div>
                @endif
                <div class="p-3">
                    <span style="background:#f1f5f9;color:#475569;padding:2px 8px;border-radius:5px;font-size:0.68rem;font-weight:600;">
                        {{ $producto->categoria }}
                    </span>
                    <div style="font-size:0.88rem;font-weight:600;color:#334155;margin-top:8px;margin-bottom:4px;">
                        {{ Str::limit($producto->nombre, 45) }}
                    </div>
                    <div style="font-size:0.72rem;color:#94a3b8;margin-bottom:10px;">
                        Por {{ $producto->proveedor->name }}
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div style="font-size:1rem;font-weight:700;color:#0f172a;">
                            {{ $producto->precio_formateado }}
                        </div>
                        <div style="font-size:0.72rem;color:{{ $producto->stock > 0 ? '#16a34a' : '#dc2626' }};font-weight:600;">
                            {{ $producto->stock > 0 ? $producto->stock.' disp.' : 'Agotado' }}
                        </div>
                    </div>
                    <a href="{{ route('cliente.productos.show', $producto) }}"
                       class="btn btn-primary w-100 mt-3" style="border-radius:9px;font-size:0.82rem;padding:8px;">
                        Ver producto
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $productos->links() }}</div>
@endif
@endsection
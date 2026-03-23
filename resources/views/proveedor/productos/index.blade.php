@extends('layouts.app')
@section('title', 'Mis Productos')
@section('page-title', 'Mis Productos')
@section('page-subtitle', 'Gestiona tu catálogo de productos')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div></div>
    <a href="{{ route('proveedor.productos.create') }}" class="btn btn-primary" style="border-radius:10px;font-size:0.85rem;padding:9px 18px;">
        <i class="bi bi-plus-lg me-2"></i>Nuevo producto
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

<div class="content-card">
    @if($productos->isEmpty())
        <div class="text-center py-5">
            <div class="stat-icon icon-purple mx-auto mb-3" style="width:56px;height:56px;font-size:1.5rem;">
                <i class="bi bi-box-seam"></i>
            </div>
            <div style="font-size:0.95rem;font-weight:600;color:#334155;">No tienes productos aún</div>
            <div style="font-size:0.8rem;color:#94a3b8;margin-top:4px;">Crea tu primer producto para empezar a vender</div>
            <a href="{{ route('proveedor.productos.create') }}" class="btn btn-primary mt-3" style="border-radius:10px;font-size:0.85rem;">
                <i class="bi bi-plus-lg me-2"></i>Crear producto
            </a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table align-middle" style="font-size:0.83rem;">
                <thead>
                    <tr style="color:#94a3b8;border-bottom:1px solid #f1f5f9;">
                        <th style="font-weight:500;border:none;padding-bottom:14px;">Producto</th>
                        <th style="font-weight:500;border:none;padding-bottom:14px;">Categoría</th>
                        <th style="font-weight:500;border:none;padding-bottom:14px;">Precio</th>
                        <th style="font-weight:500;border:none;padding-bottom:14px;">Stock</th>
                        <th style="font-weight:500;border:none;padding-bottom:14px;">Peso</th>
                        <th style="font-weight:500;border:none;padding-bottom:14px;">Estado</th>
                        <th style="font-weight:500;border:none;padding-bottom:14px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                    <tr style="border-color:#f8fafc;">
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($producto->imagen)
                                    <img src="{{ asset('storage/'.$producto->imagen) }}"
                                         style="width:42px;height:42px;object-fit:cover;border-radius:10px;border:1px solid #f1f5f9;">
                                @else
                                    <div class="stat-icon icon-purple" style="width:42px;height:42px;font-size:1rem;flex-shrink:0;">
                                        <i class="bi bi-box"></i>
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight:600;color:#334155;">{{ $producto->nombre }}</div>
                                    <div style="font-size:0.72rem;color:#94a3b8;">{{ Str::limit($producto->descripcion, 40) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="background:#f1f5f9;color:#475569;padding:3px 10px;border-radius:6px;font-size:0.72rem;font-weight:600;">
                                {{ $producto->categoria }}
                            </span>
                        </td>
                        <td style="font-weight:700;color:#0f172a;">{{ $producto->precio_formateado }}</td>
                        <td>
                            <span style="color:{{ $producto->stock > 0 ? '#16a34a' : '#dc2626' }};font-weight:600;">
                                {{ $producto->stock }} unid.
                            </span>
                        </td>
                        <td style="color:#64748b;">{{ $producto->peso }} kg</td>
                        <td>
                            @if($producto->activo)
                                <span style="background:#dcfce7;color:#16a34a;padding:3px 10px;border-radius:6px;font-size:0.72rem;font-weight:600;">Activo</span>
                            @else
                                <span style="background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:6px;font-size:0.72rem;font-weight:600;">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('proveedor.productos.edit', $producto) }}"
                                   class="btn btn-sm" style="background:#f1f5f9;color:#475569;border-radius:7px;font-size:0.75rem;padding:5px 10px;">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('proveedor.productos.destroy', $producto) }}"
                                      onsubmit="return confirm('¿Eliminar este producto?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border-radius:7px;font-size:0.75rem;padding:5px 10px;border:none;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $productos->links() }}</div>
    @endif
</div>
@endsection
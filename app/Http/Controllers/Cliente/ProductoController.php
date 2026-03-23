<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends BaseController
{
    public function index(Request $request)
    {
        $query = Producto::activos()->with('proveedor');

        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }

        $productos = $query->orderBy('created_at', 'desc')->paginate(12);
        $categorias = Producto::activos()->distinct()->pluck('categoria');

        return view('cliente.productos.index', compact('productos', 'categorias'));
    }

    public function show(Producto $producto)
    {
        abort_if(!$producto->activo, 404);
        return view('cliente.productos.show', compact('producto'));
    }
}
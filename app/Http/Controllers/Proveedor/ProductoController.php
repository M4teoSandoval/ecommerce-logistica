<?php

namespace App\Http\Controllers\Proveedor;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::deProveedor(Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('proveedor.productos.index', compact('productos'));
    }

    public function create()
    {
        return view('proveedor.productos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio'      => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'peso'        => 'required|numeric|min:0',
            'alto'        => 'nullable|numeric|min:0',
            'ancho'       => 'nullable|numeric|min:0',
            'largo'       => 'nullable|numeric|min:0',
            'categoria'   => 'required|string',
            'imagen'      => 'nullable|image|max:2048',
        ]);

        $data = $request->except('imagen');
        $data['user_id'] = Auth::id();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create($data);

        return redirect()->route('proveedor.productos.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Producto $producto)
    {
        abort_if($producto->user_id !== Auth::id(), 403);
        return view('proveedor.productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        abort_if($producto->user_id !== Auth::id(), 403);

        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio'      => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'peso'        => 'required|numeric|min:0',
            'alto'        => 'nullable|numeric|min:0',
            'ancho'       => 'nullable|numeric|min:0',
            'largo'       => 'nullable|numeric|min:0',
            'categoria'   => 'required|string',
            'imagen'      => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['imagen', '_method', '_token']);
        $data['activo'] = $request->has('activo');

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);

        return redirect()->route('proveedor.productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        abort_if($producto->user_id !== Auth::id(), 403);

        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('proveedor.productos.index')
            ->with('success', 'Producto eliminado.');
    }
}
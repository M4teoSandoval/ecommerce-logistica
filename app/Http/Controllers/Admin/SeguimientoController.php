<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Seguimiento;
use Illuminate\Http\Request;

class SeguimientoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['cliente', 'ultimoSeguimiento'])
            ->whereNotIn('estado', ['entregado', 'cancelado'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.seguimiento.index', compact('pedidos'));
    }

    public function actualizar(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado'               => 'required|in:pendiente,confirmado,preparando,en_camino,cerca,entregado,cancelado',
            'descripcion'          => 'required|string|max:255',
            'latitud'              => 'nullable|numeric',
            'longitud'             => 'nullable|numeric',
            'ubicacion_descripcion'=> 'nullable|string|max:255',
        ]);

        Seguimiento::create([
            'pedido_id'            => $pedido->id,
            'estado'               => $request->estado,
            'descripcion'          => $request->descripcion,
            'latitud'              => $request->latitud,
            'longitud'             => $request->longitud,
            'ubicacion_descripcion'=> $request->ubicacion_descripcion,
        ]);

        $pedido->update(['estado' => $request->estado]);

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    public function pedido(Pedido $pedido)
    {
        $pedido->load(['cliente', 'seguimientos', 'items.producto', 'ultimoSeguimiento']);
        return view('admin.seguimiento.pedido', compact('pedido'));
    }
}
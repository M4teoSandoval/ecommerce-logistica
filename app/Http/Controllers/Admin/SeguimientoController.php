<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Seguimiento;
use App\Models\User;
use Illuminate\Http\Request;

class SeguimientoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['cliente', 'ultimoSeguimiento', 'repartidor'])
            ->whereNotIn('estado', ['entregado', 'cancelado'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $repartidores = User::where('role', 'repartidor')->get();

        return view('admin.seguimiento.index', compact('pedidos', 'repartidores'));
    }

    public function asignarRepartidor(Request $request, Pedido $pedido)
    {
        $request->validate([
            'repartidor_id' => 'required|exists:users,id',
        ]);

        $pedido->update(['repartidor_id' => $request->repartidor_id]);

        return back()->with('success', 'Repartidor asignado correctamente.');
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
        $repartidores = User::where('role', 'repartidor')->get();
        return view('admin.seguimiento.pedido', compact('pedido', 'repartidores'));
    }
}
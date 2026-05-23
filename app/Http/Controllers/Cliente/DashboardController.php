<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pedidos = Pedido::where('user_id', $user->id)
            ->with('items.producto')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPedidos = $pedidos->count();
        $enCamino = $pedidos->where('estado', 'en_camino')->count();
        $entregados = $pedidos->where('estado', 'entregado')->count();
        $pendientes = $pedidos->whereIn('estado', ['pendiente', 'confirmado'])->count();
        $recientes = $pedidos->take(5);

        return view('cliente.dashboard', compact(
            'user', 'pedidos', 'totalPedidos', 'enCamino', 'entregados', 'pendientes', 'recientes'
        ));
    }
}

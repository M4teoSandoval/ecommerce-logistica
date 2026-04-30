<?php

namespace App\Http\Controllers\Proveedor;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;

class EnvioController extends Controller
{
    public function index()
    {
        $envios = Pedido::deProveedor(Auth::id())
            ->whereIn('estado', ['preparando', 'en_camino'])
            ->with(['cliente', 'ultimoSeguimiento'])
            ->orderBy('created_at', 'desc')
            ->get();

        $enPreparacion = Pedido::deProveedor(Auth::id())->where('estado', 'preparando')->count();
        $enCamino = Pedido::deProveedor(Auth::id())->where('estado', 'en_camino')->count();

        return view('proveedor.envios.index', compact('envios', 'enPreparacion', 'enCamino'));
    }
}

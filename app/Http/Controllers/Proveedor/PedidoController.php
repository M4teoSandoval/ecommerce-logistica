<?php

namespace App\Http\Controllers\Proveedor;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $query = Pedido::deProveedor(Auth::id())
            ->with(['cliente', 'ultimoSeguimiento']);

        $estado = $request->query('estado');
        $validEstados = ['pendiente', 'confirmado', 'preparando', 'en_camino', 'entregado', 'cancelado'];

        if ($estado && in_array($estado, $validEstados)) {
            $query->where('estado', $estado);
        }

        $pedidos = $query->orderBy('created_at', 'desc')->paginate(15);

        $counters = [
            'todos' => Pedido::deProveedor(Auth::id())->count(),
            'pendiente' => Pedido::deProveedor(Auth::id())->where('estado', 'pendiente')->count(),
            'confirmado' => Pedido::deProveedor(Auth::id())->where('estado', 'confirmado')->count(),
            'preparando' => Pedido::deProveedor(Auth::id())->where('estado', 'preparando')->count(),
            'en_camino' => Pedido::deProveedor(Auth::id())->where('estado', 'en_camino')->count(),
            'entregado' => Pedido::deProveedor(Auth::id())->where('estado', 'entregado')->count(),
        ];

        return view('proveedor.pedidos.index', compact('pedidos', 'counters', 'estado'));
    }

    public function show(Pedido $pedido)
    {
        abort_if(!$pedido->tieneProductosDeProveedor(Auth::id()), 403);

        $pedido->load(['cliente', 'items.producto', 'seguimientos']);

        $itemsProveedor = $pedido->itemsDeProveedor(Auth::id())->get();

        $estadosDisponibles = $this->estadosSiguientes($pedido->estado);

        return view('proveedor.pedidos.show', compact('pedido', 'itemsProveedor', 'estadosDisponibles'));
    }

    public function actualizarEstado(Request $request, Pedido $pedido)
    {
        abort_if(!$pedido->tieneProductosDeProveedor(Auth::id()), 403);

        $estadosPermitidos = ['confirmado', 'preparando', 'en_camino'];
        $nuevoEstado = $request->estado;

        abort_if(!in_array($nuevoEstado, $estadosPermitidos), 403, 'Estado no permitido para proveedores.');

        $orden = ['pendiente', 'confirmado', 'preparando', 'en_camino'];
        $indiceActual = array_search($pedido->estado, $orden);
        $indiceNuevo = array_search($nuevoEstado, $orden);

        abort_if($indiceNuevo <= $indiceActual, 403, 'No puedes retroceder el estado de un pedido.');

        $descripcion = $request->descripcion ?? $this->descripcionDefault($nuevoEstado);

        Seguimiento::create([
            'pedido_id'            => $pedido->id,
            'estado'               => $nuevoEstado,
            'descripcion'          => $descripcion,
            'ubicacion_descripcion'=> 'Taller del proveedor',
        ]);

        $pedido->update(['estado' => $nuevoEstado]);

        return back()->with('success', 'Estado del pedido actualizado a: ' . ucfirst(str_replace('_', ' ', $nuevoEstado)));
    }

    private function estadosSiguientes(string $estadoActual): array
    {
        $orden = ['pendiente', 'confirmado', 'preparando', 'en_camino'];
        $indice = array_search($estadoActual, $orden);

        if ($indice === false || $indice >= count($orden) - 1) {
            return [];
        }

        return array_slice($orden, $indice + 1);
    }

    private function descripcionDefault(string $estado): string
    {
        return match ($estado) {
            'confirmado' => 'Pedido confirmado por el proveedor.',
            'preparando' => 'Productos siendo preparados para envío.',
            'en_camino'  => 'Pedido listo para recolección por logística.',
            default      => 'Estado actualizado por el proveedor.',
        };
    }
}

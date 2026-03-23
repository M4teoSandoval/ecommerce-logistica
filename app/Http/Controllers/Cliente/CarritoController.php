<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\CarritoItem;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CarritoController extends Controller
{
    public function index()
    {
        $items = CarritoItem::where('user_id', Auth::id())
            ->with('producto')
            ->get();

        $subtotal = $items->sum(fn($i) => $i->cantidad * $i->producto->precio);

        return view('cliente.carrito.index', compact('items', 'subtotal'));
    }

    public function agregar(Request $request, Producto $producto)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1|max:' . $producto->stock,
        ]);

        $item = CarritoItem::where('user_id', Auth::id())
            ->where('producto_id', $producto->id)
            ->first();

        if ($item) {
            $nuevaCantidad = min($item->cantidad + $request->cantidad, $producto->stock);
            $item->update(['cantidad' => $nuevaCantidad]);
        } else {
            CarritoItem::create([
                'user_id'     => Auth::id(),
                'producto_id' => $producto->id,
                'cantidad'    => $request->cantidad,
            ]);
        }

        return redirect()->route('cliente.carrito.index')
            ->with('success', 'Producto agregado al carrito.');
    }

    public function actualizar(Request $request, CarritoItem $item)
    {
        abort_if($item->user_id !== Auth::id(), 403);

        $request->validate([
            'cantidad' => 'required|integer|min:1|max:' . $item->producto->stock,
        ]);

        $item->update(['cantidad' => $request->cantidad]);

        return redirect()->route('cliente.carrito.index');
    }

    public function eliminar(CarritoItem $item)
    {
        abort_if($item->user_id !== Auth::id(), 403);
        $item->delete();
        return redirect()->route('cliente.carrito.index')
            ->with('success', 'Producto eliminado del carrito.');
    }

    public function checkout()
    {
        $items = CarritoItem::where('user_id', Auth::id())
            ->with('producto')
            ->get();

        abort_if($items->isEmpty(), 302, redirect()->route('cliente.carrito.index'));

        $subtotal = $items->sum(fn($i) => $i->cantidad * $i->producto->precio);

        return view('cliente.carrito.checkout', compact('items', 'subtotal'));
    }

    public function procesarPedido(Request $request)
    {
        $request->validate([
            'direccion_entrega' => 'required|string|max:255',
            'ciudad'            => 'required|string|max:100',
            'telefono'          => 'required|string|max:20',
            'transporte'        => 'required|in:dron,moto,furgoneta',
            'notas'             => 'nullable|string',
        ]);

        $items = CarritoItem::where('user_id', Auth::id())
            ->with('producto')
            ->get();

        abort_if($items->isEmpty(), 403);

        $subtotal = $items->sum(fn($i) => $i->cantidad * $i->producto->precio);

        $costoEnvio = match($request->transporte) {
            'dron'      => 8000,
            'moto'      => 5000,
            'furgoneta' => 12000,
            default     => 5000,
        };

        DB::transaction(function () use ($request, $items, $subtotal, $costoEnvio) {
            $pedido = Pedido::create([
                'user_id'           => Auth::id(),
                'direccion_entrega' => $request->direccion_entrega,
                'ciudad'            => $request->ciudad,
                'telefono'          => $request->telefono,
                'transporte'        => $request->transporte,
                'estado'            => 'pendiente',
                'subtotal'          => $subtotal,
                'costo_envio'       => $costoEnvio,
                'total'             => $subtotal + $costoEnvio,
                'notas'             => $request->notas,
            ]);

            foreach ($items as $item) {
                PedidoItem::create([
                    'pedido_id'      => $pedido->id,
                    'producto_id'    => $item->producto_id,
                    'cantidad'       => $item->cantidad,
                    'precio_unitario'=> $item->producto->precio,
                    'subtotal'       => $item->cantidad * $item->producto->precio,
                ]);

                // Descontar stock
                $item->producto->decrement('stock', $item->cantidad);
            }

            // Vaciar carrito
            CarritoItem::where('user_id', Auth::id())->delete();
        });

        return redirect()->route('cliente.pedidos.index')
            ->with('success', '¡Pedido realizado exitosamente!');
    }

    public function pedidos()
    {
        $pedidos = Pedido::where('user_id', Auth::id())
            ->with('items.producto')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.pedidos.index', compact('pedidos'));
    }
}
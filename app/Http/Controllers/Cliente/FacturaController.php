<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use Illuminate\Support\Facades\Auth;

class FacturaController extends Controller
{
    public function index()
    {
        $facturas = Factura::where('user_id', Auth::id())
            ->with('pedido')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.facturas.index', compact('facturas'));
    }

    public function show(Factura $factura)
    {
        abort_if($factura->user_id !== Auth::id(), 403);

        $factura->load('pedido.items.producto', 'cliente');

        return view('cliente.facturas.show', compact('factura'));
    }

    public function descargarXml(Factura $factura)
    {
        abort_if($factura->user_id !== Auth::id(), 403);

        return response($factura->xml_generado, 200, [
            'Content-Type'        => 'application/xml',
            'Content-Disposition' => 'attachment; filename="' . $factura->numero_factura . '.xml"',
        ]);
    }
}

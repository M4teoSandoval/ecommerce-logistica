<?php

namespace App\Services;

use App\Models\Factura;
use App\Models\Pedido;

class FacturacionService
{
    public function generar(Pedido $pedido): Factura
    {
        $subtotal = $pedido->subtotal;
        $iva = round($subtotal * 0.19);
        $total = $subtotal + $iva;

        $ultimaFactura = Factura::latest('id')->first();
        $numero = $ultimaFactura ? $ultimaFactura->id + 1 : 1;
        $numeroFactura = 'FAC-' . now()->format('Y') . '-' . str_pad($numero, 5, '0', STR_PAD_LEFT);

        $cliente = $pedido->cliente;
        $items = $pedido->items()->with('producto')->get();

        $itemsXml = '';
        foreach ($items as $item) {
            $nombre = htmlspecialchars($item->producto->nombre ?? 'Producto', ENT_XML1, 'UTF-8');
            $itemsXml .= <<<XML
            <item>
                <codigo>{$item->producto_id}</codigo>
                <descripcion>{$nombre}</descripcion>
                <cantidad>{$item->cantidad}</cantidad>
                <precio_unitario>{$item->precio_unitario}</precio_unitario>
                <subtotal>{$item->subtotal}</subtotal>
            </item>
XML;
        }

        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<factura_electronica version="1.0">
    <encabezado>
        <numero>{$numeroFactura}</numero>
        <fecha_emision>{$pedido->created_at->format('Y-m-d H:i:s')}</fecha_emision>
        <tipo_documento>Factura Electrónica</tipo_documento>
        <ambiente>Pruebas</ambiente>
    </encabezado>
    <emisor>
        <nit>900.123.456-7</nit>
        <razon_social>SwiftDrop S.A.S.</razon_social>
        <direccion>Cra 45 # 67-89</direccion>
        <ciudad>Bogotá D.C.</ciudad>
        <telefono>+57 1 234 5678</telefono>
        <correo>facturas@swiftdrop.co</correo>
    </emisor>
    <adquiriente>
        <tipo_documento>CC</tipo_documento>
        <numero_documento>{$cliente->id}</numero_documento>
        <nombre>{$cliente->name}</nombre>
        <correo>{$cliente->email}</correo>
    </adquiriente>
    <detalle>
{$itemsXml}
    </detalle>
    <resumen>
        <subtotal>{$subtotal}</subtotal>
        <iva porcentaje="19.00">{$iva}</iva>
        <total>{$total}</total>
        <moneda>COP</moneda>
        <forma_pago>Pago electrónico</forma_pago>
    </resumen>
    <qr>https://swiftdrop.co/factura/{$numeroFactura}?hash=simulado_hash_dian_{$numeroFactura}</qr>
    <cufe>simulado_cufe_{$numeroFactura}_{$pedido->id}_{$total}</cufe>
</factura_electronica>
XML;

        return Factura::create([
            'pedido_id'     => $pedido->id,
            'user_id'       => $pedido->user_id,
            'numero_factura'=> $numeroFactura,
            'subtotal'      => $subtotal,
            'iva'           => $iva,
            'total'         => $total,
            'xml_generado'  => $xml,
        ]);
    }
}

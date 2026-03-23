<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drone;
use Illuminate\Http\Request;

class SimulacionController extends Controller
{
    public function index()
    {
        $drones = Drone::where('estado', 'disponible')->get();
        return view('admin.simulacion.index', compact('drones'));
    }

    public function simular(Request $request)
    {
        $request->validate([
            'drone_id'   => 'required|exists:drones,id',
            'distancia'  => 'required|numeric|min:0.1|max:500',
            'peso_carga' => 'required|numeric|min:0',
        ]);

        $drone = Drone::findOrFail($request->drone_id);

        $resultado = $drone->simularRuta((float) $request->distancia);
        $resultado['peso_carga']   = $request->peso_carga;
        $resultado['peso_ok']      = $request->peso_carga <= $drone->peso_maximo;
        $resultado['drone']        = $drone;

        return view('admin.simulacion.resultado', compact('resultado', 'drone'));
    }
}
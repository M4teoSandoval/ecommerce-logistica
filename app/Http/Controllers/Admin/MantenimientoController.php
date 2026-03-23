<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drone;
use App\Models\Mantenimiento;
use Illuminate\Http\Request;

class MantenimientoController extends Controller
{
    public function index()
    {
        $mantenimientos = Mantenimiento::with('drone')
            ->orderBy('fecha', 'desc')
            ->paginate(15);

        return view('admin.mantenimientos.index', compact('mantenimientos'));
    }

    public function create()
    {
        $drones = Drone::orderBy('nombre')->get();
        return view('admin.mantenimientos.create', compact('drones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'drone_id'    => 'required|exists:drones,id',
            'tipo'        => 'required|in:preventivo,correctivo,incidente',
            'fecha'       => 'required|date',
            'descripcion' => 'required|string',
            'costo'       => 'nullable|numeric|min:0',
            'tecnico'     => 'nullable|string|max:255',
            'estado'      => 'required|in:programado,en_proceso,completado',
        ]);

        Mantenimiento::create($request->all());

        if ($request->estado === 'completado') {
            $drone = Drone::find($request->drone_id);
            $drone->update([
                'estado' => 'disponible',
                'horas_vuelo_desde_mantenimiento' => 0,
            ]);
        } else {
            Drone::find($request->drone_id)->update(['estado' => 'mantenimiento']);
        }

        return redirect()->route('admin.mantenimientos.index')
            ->with('success', 'Mantenimiento registrado correctamente.');
    }

    public function update(Request $request, Mantenimiento $mantenimiento)
    {
        $request->validate([
            'estado' => 'required|in:programado,en_proceso,completado',
        ]);

        $mantenimiento->update(['estado' => $request->estado]);

        if ($request->estado === 'completado') {
            $mantenimiento->drone->update([
                'estado' => 'disponible',
                'horas_vuelo_desde_mantenimiento' => 0,
            ]);
        }

        return back()->with('success', 'Estado actualizado.');
    }
}
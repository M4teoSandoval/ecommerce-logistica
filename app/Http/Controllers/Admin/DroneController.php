<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drone;
use Illuminate\Http\Request;

class DroneController extends Controller
{
    public function index()
    {
        $drones = Drone::withCount('mantenimientos')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total'        => $drones->count(),
            'disponibles'  => $drones->where('estado', 'disponible')->count(),
            'mantenimiento'=> $drones->where('estado', 'mantenimiento')->count(),
            'alertas'      => $drones->filter(fn($d) => $d->necesita_mantenimiento)->count(),
        ];

        return view('admin.drones.index', compact('drones', 'stats'));
    }

    public function create()
    {
        return view('admin.drones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'             => 'required|string|max:255',
            'modelo'             => 'nullable|string|max:255',
            'peso_maximo'        => 'required|numeric|min:0',
            'distancia_maxima'   => 'required|numeric|min:0',
            'altitud_maxima'     => 'required|numeric|min:0',
            'velocidad_promedio' => 'required|numeric|min:1',
            'capacidad_bateria'  => 'required|numeric|min:1',
            'consumo_por_km'     => 'required|numeric|min:0',
            'horas_alerta_mantenimiento' => 'required|numeric|min:1',
            'notas'              => 'nullable|string',
        ]);

        Drone::create($request->all());

        return redirect()->route('admin.drones.index')
            ->with('success', 'Dron registrado exitosamente.');
    }

    public function show(Drone $drone)
    {
        $mantenimientos = $drone->mantenimientos()
            ->orderBy('fecha', 'desc')
            ->get();

        return view('admin.drones.show', compact('drone', 'mantenimientos'));
    }

    public function edit(Drone $drone)
    {
        return view('admin.drones.edit', compact('drone'));
    }

    public function update(Request $request, Drone $drone)
    {
        $request->validate([
            'nombre'             => 'required|string|max:255',
            'modelo'             => 'nullable|string|max:255',
            'peso_maximo'        => 'required|numeric|min:0',
            'distancia_maxima'   => 'required|numeric|min:0',
            'altitud_maxima'     => 'required|numeric|min:0',
            'velocidad_promedio' => 'required|numeric|min:1',
            'capacidad_bateria'  => 'required|numeric|min:1',
            'consumo_por_km'     => 'required|numeric|min:0',
            'estado'             => 'required|in:disponible,en_vuelo,mantenimiento,inactivo',
            'horas_alerta_mantenimiento' => 'required|numeric|min:1',
            'notas'              => 'nullable|string',
        ]);

        if ($request->estado === 'disponible') {
            $drone->horas_vuelo_desde_mantenimiento = 0;
        }

        $drone->update($request->all());

        return redirect()->route('admin.drones.index')
            ->with('success', 'Dron actualizado correctamente.');
    }

    public function destroy(Drone $drone)
    {
        $drone->delete();
        return redirect()->route('admin.drones.index')
            ->with('success', 'Dron eliminado.');
    }
}
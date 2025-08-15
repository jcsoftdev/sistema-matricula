<?php

namespace App\Http\Controllers;

use App\Models\PeriodoAcademico;
use Illuminate\Http\Request;

class PeriodoAcademicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periodos = PeriodoAcademico::orderBy('fecha_inicio', 'desc')->get();
        return view('periodos.index', compact('periodos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('periodos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'fecha_inicio_prematricula' => 'nullable|date',
            'fecha_fin_prematricula' => 'nullable|date|after:fecha_inicio_prematricula',
            'costo_matricula' => 'required|numeric|min:0',
            'costo_mensualidad' => 'required|numeric|min:0',
            'descuento_maximo' => 'nullable|numeric|min:0|max:100',
        ]);

        $periodo = PeriodoAcademico::create($request->all());

        if ($request->has('activo') && $request->activo) {
            PeriodoAcademico::activarPeriodo($periodo->id);
        }

        return redirect()->route('periodos.index')
            ->with('success', 'Período académico creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PeriodoAcademico $periodo)
    {
        return view('periodos.show', compact('periodo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PeriodoAcademico $periodo)
    {
        return view('periodos.edit', compact('periodo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PeriodoAcademico $periodo)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'fecha_inicio_prematricula' => 'nullable|date',
            'fecha_fin_prematricula' => 'nullable|date|after:fecha_inicio_prematricula',
            'costo_matricula' => 'required|numeric|min:0',
            'costo_mensualidad' => 'required|numeric|min:0',
            'descuento_maximo' => 'nullable|numeric|min:0|max:100',
        ]);

        $periodo->update($request->all());

        if ($request->has('activo') && $request->activo) {
            PeriodoAcademico::activarPeriodo($periodo->id);
        } elseif (!$request->has('activo') && $periodo->activo) {
            $periodo->update(['activo' => false]);
        }

        return redirect()->route('periodos.index')
            ->with('success', 'Período académico actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PeriodoAcademico $periodo)
    {
        // Check if this period has any matriculas
        if ($periodo->matriculas()->count() > 0) {
            return redirect()->route('periodos.index')
                ->withErrors(['error' => 'No se puede eliminar un período que tiene matrículas asociadas.']);
        }

        $periodo->delete();

        return redirect()->route('periodos.index')
            ->with('success', 'Período académico eliminado exitosamente.');
    }

    /**
     * Activate a specific academic period
     */
    public function activate(PeriodoAcademico $periodo)
    {
        PeriodoAcademico::activarPeriodo($periodo->id);

        return redirect()->route('periodos.index')
            ->with('success', 'Período académico activado exitosamente.');
    }
}

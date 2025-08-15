<?php

namespace App\Http\Controllers;

use App\Events\PrematriculaSubmitted;
use App\Models\Estudiante;
use App\Models\Matricula;
use App\Models\PeriodoAcademico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrematriculaController extends Controller{
    public function index()
    {
        $user = Auth::user();
        $apoderado = $user->apoderado;

        if (!$apoderado) {
            return redirect()->route('home')->with('error', 'No se encontró información del apoderado.');
        }

        // Get active academic period
        $periodoActivo = PeriodoAcademico::getActivo();

        if (!$periodoActivo) {
            return view('prematricula.index', [
                'apoderado' => $apoderado,
                'periodoActivo' => null,
                'mensaje' => 'No hay período académico activo. Contacte con la administración.'
            ]);
        }

        // Check if prematricula period is open
        if (!$periodoActivo->isPrematriculaAberta()) {
            return view('prematricula.index', [
                'apoderado' => $apoderado,
                'periodoActivo' => $periodoActivo,
                'mensaje' => 'El período de prematrícula está cerrado.'
            ]);
        }

        // Get students with their matricula status for this period
        $estudiantes = $apoderado->estudiantes()->with(['matriculas' => function($query) use ($periodoActivo) {
            $query->where('periodo_academico_id', $periodoActivo->id);
        }])->get();

        return view('prematricula.index', compact('apoderado', 'periodoActivo', 'estudiantes'));
    }

    public function store(Request $request)
    {
        // Debug: Log the incoming request data
        \Log::info('Prematricula store request:', $request->all());

        $user = Auth::user();
        $apoderado = $user->apoderado;

        if (!$apoderado) {
            \Log::error('No apoderado found for user: ' . $user->id);
            return redirect()->route('prematricula.index')->with('error', 'No se encontró información del apoderado.');
        }

        // Get active academic period
        $periodoActivo = PeriodoAcademico::getActivo();
        if (!$periodoActivo) {
            \Log::error('No active academic period found');
            return redirect()->route('prematricula.index')->with('error', 'No hay período académico activo.');
        }

        \Log::info('Active period:', ['period' => $periodoActivo->toArray()]);

        if (!$periodoActivo->isPrematriculaAberta()) {
            \Log::error('Prematricula period is closed');
            return redirect()->route('prematricula.index')->with('error', 'El período de prematrícula está cerrado.');
        }

        \Log::info('About to validate request');
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'grado_postula' => 'required|string|max:50',
            'nivel' => 'required|in:inicial,primaria,secundaria',
        ]);
        \Log::info('Validation passed');

        // Verify that the student belongs to this apoderado
        $estudiante = $apoderado->estudiantes()->find($request->estudiante_id);
        if (!$estudiante) {
            return redirect()->route('prematricula.index')->with('error', 'El estudiante seleccionado no pertenece a este apoderado.');
        }

        // Check if student already has a matricula for this period
        $existeMatricula = Matricula::where('estudiante_id', $request->estudiante_id)
                                   ->where('periodo_academico_id', $periodoActivo->id)
                                   ->exists();

        if ($existeMatricula) {
            return redirect()->route('prematricula.index')->with('error', 'El estudiante ya tiene una matrícula registrada para este período académico.');
        }

        // Create matricula with prematricula status
        $matriculaCode = 'PRE-' . $periodoActivo->id . '-' . $estudiante->dni_estudiante;

        \Log::info('About to create matricula with code: ' . $matriculaCode);

        $matricula = Matricula::create([
            'periodo_academico_id' => $periodoActivo->id,
            'estudiante_id' => $request->estudiante_id,
            'apoderado_id' => $apoderado->id,
            'cod_matricula' => $matriculaCode,
            'nivel' => $request->nivel,
            'grado' => $request->grado_postula,
            'seccion' => 'A', // Default section
            'situacion' => 'nuevo',
            'procedencia' => 'externo',
            'ie_procedencia' => null,
            'matricula_costo' => $periodoActivo->costo_matricula,
            'mensualidad' => $periodoActivo->costo_mensualidad,
            'descuento' => 0.00,
            'total' => $periodoActivo->costo_matricula + $periodoActivo->costo_mensualidad,
            'deuda' => $periodoActivo->costo_matricula + $periodoActivo->costo_mensualidad,
            'dia_pago' => 15, // Default payment day
            'parentesco' => 'padre',
            'status' => 'prematricula'
        ]);

        \Log::info('Matricula created successfully:', ['id' => $matricula->id]);

        // Dispatch the event for real-time notifications
        event(new PrematriculaSubmitted($matricula));

        return redirect()->route('prematricula.index')
                         ->with('success', 'Prematrícula enviada correctamente para el período ' . $periodoActivo->nombre . '.');
    }

    public function lista()
    {
        $periodoActivo = PeriodoAcademico::getActivo();

        $prematriculas = Matricula::where('status', 'prematricula')
                                  ->where('periodo_academico_id', $periodoActivo ? $periodoActivo->id : null)
                                  ->with(['estudiante', 'apoderado', 'periodoAcademico'])
                                  ->get();
        return view('prematricula.lista', compact('prematriculas', 'periodoActivo'));
    }

    public function aprobar($id)
    {
        $prematricula = Matricula::where('id', $id)
                                 ->where('status', 'prematricula')
                                 ->firstOrFail();

        if ($prematricula->status !== 'prematricula') {
            return back()->with('error', 'Esta prematrícula ya fue procesada.');
        }

        // Change status to matricula (approved)
        $prematricula->status = 'matricula';
        $prematricula->save();

        return back()->with('success', 'Prematrícula aprobada exitosamente. El estudiante está ahora matriculado.');
    }


    public function aprobarView()
    {
        $prematriculas = Prematricula::where('estado', 'pendiente')->get();
        return view('prematriculas.aprobar', compact('prematriculas'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $estudiantes = Estudiante::with('apoderado')->get();
        return view("estudiantes.index", compact('estudiantes'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'dni_estudiante' => 'required|digits:8|max:8|unique:estudiantes',
            'nombres_estudiante' => 'required|string',
            'apellidos_estudiante' => 'required|string',
            'genero' => 'required|string',
            'fecha_nacimiento' => 'required',
            'apoderado_id' => 'nullable|exists:apoderados,id',
        ]);


        $user = new Estudiante();
        $user->dni_estudiante = $request->dni_estudiante;
        $user->nombres_estudiante = $request->nombres_estudiante;
        $user->apellidos_estudiante = $request->apellidos_estudiante;
        $user->genero = $request->genero;
        $user->fecha_nacimiento = $request->fecha_nacimiento;
        $user->apoderado_id = $request->apoderado_id;
        $user->save();

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante '.$request->nombres_estudiante.' agregado exitosamente.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Estudiante  $estudiante
     * @return \Illuminate\Http\Response
     */
    public function show(Estudiante $estudiante)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Estudiante  $estudiante
     * @return \Illuminate\Http\Response
     */
    public function edit(Estudiante $estudiante)
    {
        $estudiante->load('apoderado');
        return view("estudiantes.edit", compact('estudiante'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estudiante  $estudiante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Estudiante $estudiante)
    {
        $this->validate($request, [
            'dni_estudiante' => 'required|digits:8|max:8',
            'nombres_estudiante' => 'required|string',
            'apellidos_estudiante' => 'required|string',
            'genero' => 'required|string',
            'fecha_nacimiento' => 'required',
            'apoderado_id' => 'nullable|exists:apoderados,id',
        ]);

        $estudiante->dni_estudiante = $request->dni_estudiante;
        $estudiante->nombres_estudiante = $request->nombres_estudiante;
        $estudiante->apellidos_estudiante = $request->apellidos_estudiante;
        $estudiante->genero = $request->genero;
        $estudiante->fecha_nacimiento = $request->fecha_nacimiento;
        $estudiante->apoderado_id = $request->apoderado_id;
        $estudiante->save();

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante '.$request->nombres_estudiante.' actualizado exitosamente.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estudiante  $estudiante
     * @return \Illuminate\Http\Response
     */
    public function destroy(Estudiante $estudiante)
    {
        $estudiante->delete();
        return redirect()->route('estudiantes.index')->with('success', 'Estudiante eliminado exitosamente.');

    }

    public function getStudentbyDNI(Request $request){
        $dni = $request->dni;
        $estudiante = Estudiante::where('dni_estudiante', $dni)->get();
        return response()->json($estudiante);
    }

}

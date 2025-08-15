<?php

namespace App\Http\Controllers;

use App\Http\Requests\MatriculaStoreRequest;
use App\Models\Banco;
use App\Models\Estudiante;
use App\Models\Matricula;
use App\Models\PeriodoAcademico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatriculaController extends Controller
{

    public $grados = [
        ["3 Años", "4 Años", "5 Años"],
        ["1°", "2°", "3°", "4°", "5°", "6°"],
        ["1°", "2°", "3°", "4°", "5°"],
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $matriculas = Matricula::all();
        return view("matriculas.index", compact('matriculas'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$bancos = Banco::all();
        $grados =  $this->grados;
        return view("matriculas.create", compact( 'grados'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MatriculaStoreRequest $request)
    {
        $periodoActivo = PeriodoAcademico::getActivo();

        if (!$periodoActivo) {
            return redirect()->back()
                ->withErrors(['error' => 'No hay un período académico activo. Configure uno antes de crear matrículas.']);
        }

        //return $request;
        $est = Estudiante::findOrFail($request->estudiante_id);

        //return $est->dni_estudiante;

        $today = getdate();
        $year  = date("y");

        $matricula = new Matricula();
        $matricula->cod_matricula = $est->dni_estudiante.$year;
        $matricula->estudiante_id = $request->estudiante_id;
        $matricula->apoderado_id = $request->apoderado_id;
        $matricula->parentesco = $request->parentesco;
        $matricula->nivel = $request->nivel;
        $matricula->grado = $request->grado;
        $matricula->seccion = $request->seccion;

        $matricula->situacion = $request->situacion;
        $matricula->procedencia = $request->procedencia;
        $matricula->ie_procedencia = $request->ie_procedencia;

        $matricula->matricula_costo = $request->matricula_costo;
        $matricula->mensualidad = $request->mensualidad_final;
        $matricula->descuento = $request->descuento;
        $matricula->dia_pago = $request->dia_pago;
        $matricula->total = $request->mensualidad_final * 10 + $request->matricula_costo;
        $matricula->deuda = $matricula->total;
        $matricula->periodo_academico_id = $periodoActivo->id;

        $matricula->save();

        return redirect()->route('matriculas.index')->with('success', 'Matrícula registrado exitosamente.');

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Matricula  $matricula
     * @return \Illuminate\Http\Response
     */
    public function edit(Matricula $matricula)
    {
        $grados =  $this->grados;
        $bancos = Banco::all();
        return view("matriculas.edit", compact('matricula', 'grados', 'bancos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Matricula  $matricula
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Matricula $matricula)
    {
        $this->validate($request, [
            'nivel' => 'required|string',
            'grado' => 'required|string',
            'seccion' => 'required|string',
            'monto' => 'required',
            'banco' => 'required|string',
            'dni_apoderado' => 'required|digits:8',
            'nombres_apoderado' => 'required|string',
            'apellidos_apoderado' => 'required|string',
        ]);

        $matricula->monto = $request->monto;
        $matricula->apoderado = $request->nombres_apoderado." ".$request->apellidos_apoderado;
        $matricula->nivel = $request->nivel;
        $matricula->grado = $request->grado;
        $matricula->seccion = $request->seccion;
        $matricula->estudiante_id = $request->estudiante_id;
        $matricula->save();

        return redirect()->route('matriculas.index')->with('success', 'Registro actualizado exitosamente.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Matricula  $matricula
     * @return \Illuminate\Http\Response
     */
    public function destroy(Matricula $matricula)
    {
        $matricula->delete();
        return redirect()->route('matriculas.index')->with('success', 'Registro eliminado exitosamente.');
    }


    public function getMatriculabyCode(Request $request){
        $code = $request->code;

        $matricula = DB::table('estudiantes')
            ->join('matriculas', 'estudiantes.id', '=', 'matriculas.estudiante_id')
            ->select('*')
            ->where('matriculas.cod_matricula', '=', $code)
            ->get();
        return response()->json($matricula);
    }

    public function getMatriculasbyAula(Request $request){
        $nivel = $request->nivel;
        $grado = $request->grado;
        $seccion = $request->seccion;

        if ($request->nivel != null && $request->grado != null && $request->seccion != null){
            $matriculas = Matricula::where('nivel', $nivel)
            ->where('grado', $grado)
            ->where('seccion', $seccion)->get();

        }else if ($request->nivel != null && $request->grado != null){
            $matriculas = Matricula::where('nivel', $nivel)->where('grado', $grado)->get();

        }else if($request->nivel != null ){
            $matriculas = Matricula::where('nivel', $nivel)->get();

        }else{
            $matriculas = Matricula::all();
            //return redirect()->back();
        }

        return view("matriculas.index", compact('matriculas', 'nivel', 'grado', 'seccion'));

    }

}

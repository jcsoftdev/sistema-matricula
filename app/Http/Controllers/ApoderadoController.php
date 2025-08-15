<?php

namespace App\Http\Controllers;

use App\Models\Apoderado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApoderadoController extends Controller
{

    public function index()
    {
        $apoderados = Apoderado::with(['user', 'matriculas'])->get();
        return view("apoderados.index", compact('apoderados'));
    }

    public function create(){
        return view("apoderados.create");
    }



    public function store(Request $request)
    {
        $this->validate($request, [
            'dni_apoderado' => 'required|digits:8|max:8|unique:apoderados',
            'nombres_apoderado' => 'required|string',
            'apellidos_apoderado' => 'required|string',
            'celular_apoderado' => 'digits:9|max:9',
            'username' => 'required|string|unique:users|min:3',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Use DNI as default password if no password provided
        $password = $request->password ?: $request->dni_apoderado;

        // Create user first
        $user = new User();
        $user->name = $request->nombres_apoderado . ' ' . $request->apellidos_apoderado;
        $user->username = $request->username;
        $user->password = Hash::make($password);
        $user->rol = 'padre';
        $user->save();

        // Create apoderado and link to user
        $apoderado = new Apoderado();
        $apoderado->dni_apoderado = $request->dni_apoderado;
        $apoderado->nombres_apoderado = $request->nombres_apoderado;
        $apoderado->apellidos_apoderado = $request->apellidos_apoderado;
        $apoderado->celular_apoderado = $request->celular_apoderado;
        $apoderado->user_id = $user->id;
        $apoderado->save();

        $passwordInfo = $request->password ? '' : ' (contraseña: DNI)';
        return redirect()->route('apoderados.index')->with('success', 'Apoderado '.$request->nombres_apoderado.' registrado exitosamente con usuario: '.$request->username.$passwordInfo);
    }



    public function edit(Apoderado $apoderado)
    {
        $apoderado->load('user');
        return view("apoderados.edit", compact('apoderado'));

    }


    public function update(Request $request, Apoderado $apoderado)
    {
        // Base validation for apoderado
        $rules = [
            'dni_apoderado' => 'required|digits:8|max:8|unique:apoderados,dni_apoderado,' . $apoderado->id,
            'nombres_apoderado' => 'required|string',
            'apellidos_apoderado' => 'required|string',
            'celular_apoderado' => 'digits:9|max:9',
        ];

        // User validation - always required since we want all apoderados to have users
        if ($apoderado->user) {
            $rules['username'] = 'required|string|min:3|unique:users,username,' . $apoderado->user->id;
        } else {
            $rules['username'] = 'required|string|min:3|unique:users,username';
        }

        // Password is required only if creating new user or if provided for existing user
        if (!$apoderado->user) {
            $rules['password'] = 'nullable|string|min:6|confirmed';
        } elseif ($request->filled('password')) {
            $rules['password'] = 'nullable|string|min:6|confirmed';
        }

        $this->validate($request, $rules);

        // Update apoderado data
        $apoderado->dni_apoderado = $request->dni_apoderado;
        $apoderado->nombres_apoderado = $request->nombres_apoderado;
        $apoderado->apellidos_apoderado = $request->apellidos_apoderado;
        $apoderado->celular_apoderado = $request->celular_apoderado;

        $message = 'Apoderado ' . $request->nombres_apoderado . ' actualizado exitosamente.';

        // Handle user creation or update
        if (!$apoderado->user) {
            // Create new user
            $password = $request->password ?: $request->dni_apoderado;

            $user = new User();
            $user->name = $request->nombres_apoderado . ' ' . $request->apellidos_apoderado;
            $user->username = $request->username;
            $user->password = Hash::make($password);
            $user->rol = 'padre';
            $user->save();

            $apoderado->user_id = $user->id;
            $passwordInfo = $request->password ? '' : ' (contraseña: DNI)';
            $message .= ' Usuario creado: ' . $request->username . $passwordInfo;

        } else {
            // Update existing user
            $user = $apoderado->user;
            $user->name = $request->nombres_apoderado . ' ' . $request->apellidos_apoderado;
            $user->username = $request->username;

            // Update password only if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
                $message .= ' Contraseña actualizada.';
            }

            $user->save();
            $message .= ' Usuario actualizado.';
        }

        $apoderado->save();

        return redirect()->route('apoderados.index')->with('success', $message);
    }


    public function destroy(Apoderado $apoderado)
    {
        // Load the user relationship and check for matriculas
        $apoderado->load(['user', 'matriculas']);

        // Check if the user is currently logged in (safety check)
        if ($apoderado->user && auth()->check() && auth()->id() === $apoderado->user->id) {
            return redirect()->route('apoderados.index')->with('error', 'No puedes eliminar tu propia cuenta desde aquí.');
        }

        // Store references before deleting
        $user = $apoderado->user;
        $nombre = $apoderado->nombres_apoderado;
        $matriculasCount = $apoderado->matriculas->count();

        // Delete the apoderado (this will cascade delete matriculas due to foreign key constraint)
        $apoderado->delete();

        // Delete the associated user if it exists
        $message = "Apoderado $nombre eliminado exitosamente";
        if ($matriculasCount > 0) {
            $message .= " junto con $matriculasCount matrícula(s) asociada(s)";
        }

        if ($user) {
            $user->delete();
            $message .= " y su cuenta de usuario";
        }

        return redirect()->route('apoderados.index')->with('success', $message . ".");
    }

    public function getApoderadobyDNI(Request $request){
        $dni = $request->dni;
        $apoderado = Apoderado::where('dni_apoderado', $dni)->get();
        return response()->json($apoderado);
    }
}

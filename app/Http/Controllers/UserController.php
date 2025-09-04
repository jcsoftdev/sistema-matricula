<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::paginate(6);

        return view("usuarios.index", compact('usuarios'));

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
            'nombre_usuario' => 'required|string',
            'username' => 'required',
            'password' => 'required|string',
            'user_rol' => 'required|string',
        ]);

        $user = new User();
        $user->name = $request->nombre_usuario;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->rol = $request->user_rol;

        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario '.$request->nombre_usuario.' registrado correctamente.');

        //return $user;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $usuario)
    {
        return view("usuarios.edit", compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $usuario)
    {

        $this->validate($request, [
            'nombre_usuario' => 'required|string',
            'username' => 'required|string',
            'user_rol' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $usuario->name = $request->nombre_usuario;
        $usuario->username = $request->username;
        // Only update password when provided
        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }
        $usuario->rol = $request->user_rol;

        $usuario->save();

        return redirect()->route('users.index')->with('success', 'Usuario '.$request->nombre_usuario.' actualizado correctamente.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $usuario)
    {
        //
        $usuario->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }


    // app/Http/Controllers/UserController.php



public function registerParent(Request $request)
{
    // Validar los datos de entrada
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',  // Confirmación de contraseña
    ]);

    // Crear un nuevo usuario con el rol de 'padre'
    User::create([
        'name' => $validatedData['name'],
        'username' => $validatedData['username'],
        'password' => bcrypt($validatedData['password']),  // Asegúrate de encriptar la contraseña
        'rol' => 'padre',  // Asignar el rol de 'padre'
    ]);

    // Redirigir al administrador con un mensaje de éxito
    return redirect()->route('admin.dashboard')->with('success', 'Padre registrado correctamente.');
}

}

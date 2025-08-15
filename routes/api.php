<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Apoderado;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/apoderados/search', function (Request $request) {
    $search = $request->get('search');

    if (empty($search)) {
        return response()->json([]);
    }

    $apoderados = Apoderado::where('nombres_apoderado', 'LIKE', "%{$search}%")
        ->orWhere('apellidos_apoderado', 'LIKE', "%{$search}%")
        ->orWhere('dni_apoderado', 'LIKE', "%{$search}%")
        ->limit(10)
        ->get(['id', 'dni_apoderado', 'nombres_apoderado', 'apellidos_apoderado']);

    return response()->json($apoderados);
});

Route::get('/apoderados/{id}/estudiantes', function ($id) {
    $apoderado = Apoderado::with('estudiantes')->find($id);

    if (!$apoderado) {
        return response()->json(['error' => 'Apoderado no encontrado'], 404);
    }

    return response()->json($apoderado->estudiantes);
});

// Create user for specific apoderado
Route::post('/apoderados/{id}/create-user', function ($id) {
    $apoderado = Apoderado::find($id);

    if (!$apoderado) {
        return response()->json(['success' => false, 'message' => 'Apoderado no encontrado'], 404);
    }

    if ($apoderado->user) {
        return response()->json(['success' => false, 'message' => 'Este apoderado ya tiene usuario']);
    }

    // Generate username: first_name.last_name
    $firstName = strtolower(explode(' ', $apoderado->nombres_apoderado)[0]);
    $lastName = strtolower(explode(' ', $apoderado->apellidos_apoderado)[0]);
    $baseUsername = $firstName . '.' . $lastName;

    // Remove accents and special characters
    $baseUsername = iconv('UTF-8', 'ASCII//TRANSLIT', $baseUsername);
    $baseUsername = preg_replace('/[^a-z0-9.]/', '', $baseUsername);

    // Check if username exists and add number if needed
    $username = $baseUsername;
    $counter = 1;
    while (User::where('username', $username)->exists()) {
        $username = $baseUsername . $counter;
        $counter++;
    }

    // Create user
    $user = new User();
    $user->name = $apoderado->nombres_apoderado . ' ' . $apoderado->apellidos_apoderado;
    $user->username = $username;
    $user->password = Hash::make($apoderado->dni_apoderado);
    $user->rol = 'apoderado';
    $user->save();

    // Link user to apoderado
    $apoderado->user_id = $user->id;
    $apoderado->save();

    return response()->json([
        'success' => true,
        'username' => $username,
        'password' => $apoderado->dni_apoderado,
        'message' => 'Usuario creado exitosamente'
    ]);
});

// Create users for all apoderados without user accounts
Route::post('/apoderados/create-users-all', function () {
    $apoderados = Apoderado::whereNull('user_id')->get();

    if ($apoderados->isEmpty()) {
        return response()->json(['success' => false, 'message' => 'Todos los apoderados ya tienen usuarios']);
    }

    $createdUsers = [];
    $errors = [];

    foreach ($apoderados as $apoderado) {
        try {
            // Generate username: first_name.last_name
            $firstName = strtolower(explode(' ', $apoderado->nombres_apoderado)[0]);
            $lastName = strtolower(explode(' ', $apoderado->apellidos_apoderado)[0]);
            $baseUsername = $firstName . '.' . $lastName;

            // Remove accents and special characters
            $baseUsername = iconv('UTF-8', 'ASCII//TRANSLIT', $baseUsername);
            $baseUsername = preg_replace('/[^a-z0-9.]/', '', $baseUsername);

            // Check if username exists and add number if needed
            $username = $baseUsername;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            // Create user
            $user = new User();
            $user->name = $apoderado->nombres_apoderado . ' ' . $apoderado->apellidos_apoderado;
            $user->username = $username;
            $user->password = Hash::make($apoderado->dni_apoderado);
            $user->rol = 'apoderado';
            $user->save();

            // Link user to apoderado
            $apoderado->user_id = $user->id;
            $apoderado->save();

            $createdUsers[] = [
                'name' => $apoderado->nombres_apoderado . ' ' . $apoderado->apellidos_apoderado,
                'username' => $username,
                'password' => $apoderado->dni_apoderado
            ];

        } catch (Exception $e) {
            $errors[] = "Error creando usuario para {$apoderado->nombres_apoderado}: " . $e->getMessage();
        }
    }

    return response()->json([
        'success' => true,
        'users' => $createdUsers,
        'errors' => $errors,
        'message' => count($createdUsers) . ' usuarios creados exitosamente'
    ]);
});

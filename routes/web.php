<?php

use App\Http\Controllers\ApoderadoController;
use App\Http\Controllers\BancoController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PeriodoAcademicoController;
use App\Http\Controllers\PrematriculaController;
use App\Http\Controllers\RandomController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::view('/', 'auth.login')->middleware('guest');

Route::view('login', 'auth.login')->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login'] )->name('login.post');
Route::post('logout', [LoginController::class, 'logout'] )->name('logout');

//Route::view('home', 'home')->name('home');
//Route::view('home', 'home')->name('home')->middleware('auth');

Route::get('home', [HomeController::class, 'index'])->name('home')->middleware('auth');

Route::resource('usuarios', UserController::class)->names('users')->middleware('auth.admin');

// Academic Periods - Admin only
Route::resource('periodos', PeriodoAcademicoController::class)->names('periodos')->middleware('auth.admin');
Route::patch('periodos/{periodo}/activate', [PeriodoAcademicoController::class, 'activate'])->name('periodos.activate')->middleware('auth.admin');

Route::resource('estudiantes', EstudianteController::class)->names('estudiantes')->middleware('auth.secretaria');
Route::post('estudiantes/getStudent', [EstudianteController::class, 'getStudentbyDNI']);
Route::resource('apoderados', ApoderadoController::class)->names('apoderados')->middleware('auth.secretaria');
Route::post('apoderados/getApoderado', [ApoderadoController::class, 'getApoderadobyDNI']);
Route::resource('matriculas', MatriculaController::class)->names('matriculas')->middleware('auth.secretaria');
Route::post('matriculas/getMatricula', [MatriculaController::class, 'getMatriculabyCode']);
Route::post('matriculas/filtrar', [MatriculaController::class, 'getMatriculasbyAula'])->name("filter");
Route::resource('bancos', BancoController::class)->names('bancos')->middleware('auth.secretaria');
Route::resource('pagos', PagoController::class)->names('pagos')->middleware('auth.secretaria');
Route::get('pagos/{pago}/invoice', [PagoController::class, 'generateInvoice'])->name('pagos.invoice');

Route::get('reportes', [ReporteController::class, 'index'])->name('reportes')->middleware('auth.secretaria');

Route::post('reportes/filtrar', [ReporteController::class, 'filterByMonth'])->name('filtermonth');


// Solo el administrador puede registrar padres
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/register', [UserController::class, 'showRegisterForm'])->name('admin.showRegisterForm');
    Route::post('/admin/register', [UserController::class, 'registerParent'])->name('admin.registerParent');
});


// Middleware para Padres
Route::middleware(['auth'/*, 'PadreAuth' */])->group(function () {
    Route::get('/prematricula', [PrematriculaController::class, 'index'])->name('prematricula.index');
    Route::post('/prematricula', [PrematriculaController::class, 'store'])->name('prematricula.store');
});

// Prematrícula management (admin/secretaria)
Route::middleware(['auth.admin'])->group(function () {
    Route::get('/prematriculas/lista', [PrematriculaController::class, 'lista'])->name('prematriculas.lista');
    Route::post('/prematricula/{id}/aprobar', [PrematriculaController::class, 'aprobar'])->name('prematricula.aprobar');
});



<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
        $user = Auth::user();

        $pagos_matricula = null;
        $ctd_matricula = null;

        if ($user && $user->rol !== 'padre') {
            $pagos_matricula = Pago::sum('monto');
            $ctd_matricula = Matricula::count('id');
        }

        return view("home", [
            'pagos_matricula' => $pagos_matricula,
            'ctd_matricula' => $ctd_matricula,
            'userRole' => $user?->rol,
        ]);
    }
}

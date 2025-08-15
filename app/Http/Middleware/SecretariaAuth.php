<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecretariaAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Si el usuario está autenticado
        if (Auth::check()) {

            // Si es administrador o secretaria -> permitir
            if (Auth::user()->rol == "secretario" || Auth::user()->rol == "admin") {
                return $next($request);
            }

            // Si es padre -> bloquear y redirigir
            if (Auth::user()->rol == "padre") {
                return redirect()->route('home')->with('error', 'No tiene permisos para acceder a este módulo.');
            }
        }

        // Cualquier otro caso -> redirigir
        return redirect()->route("home");
    }
}

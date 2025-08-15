<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockPadreAccess
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
        // Verifica si el usuario está autenticado y si su rol es 'padre'
        if (auth()->check() && auth()->user()->rol == 'padre') {
            // Si está intentando acceder a una ruta restringida para padres, redirigirlo
            if (in_array($request->route()->getName(), ['bancos.index', 'bancos.create', 'bancos.edit', 'bancos.delete'])) {
                return redirect()->route('home')->with('error', 'No tiene permisos para acceder a este módulo.');
            }
        }

        return $next($request);
    }
}

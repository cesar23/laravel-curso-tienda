<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {

            //--- Tipo de usuario = user
            // si el usuario quiere  entrar al detalle del pedido (que es cuando un usuario logeado ve el detalle de su pedido)
            if($request->path() == 'order-detail') return $next($request);

            //--- Tipo de usuario = admin
            //---para entrar al panel del administrador
            if(auth()->user()->type != 'admin'){
                $message = 'Permiso denegado: Solo los administradores pueden entrar a esta sección';
                return redirect()->route('home')->with('message', $message);
            }



           // return redirect('/home');
        }


        return $next($request);


    }
}

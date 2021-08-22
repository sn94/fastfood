<?php

namespace App\Http\Middleware;

use Closure;
 

class CajaAuth
{

    public function handle($request, Closure $next)
    {



        $sess =  $request->session();
        $accessLogin =  $request->is("usuario/sign-in");


        //Si no existe sesion Usuario y no accede al inicio de sesion
        if (!$sess->has("USUARIO")  &&  !$accessLogin) {
            if(  $request->ajax())
            return response()->json(['err'=> "Su sesión ha caducado"]);
            else
            return redirect('usuario/sign-in');
        }
        if (($sess->get("NIVEL")  ==  "GOD"  || $sess->get("NIVEL")  ==  "SUPER") ||  $sess->get("NIVEL")  ==  "CAJA")
            //permitir
            return $next($request);
        else
            return redirect('usuario/sign-in');
    }
}

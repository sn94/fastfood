<?php

namespace App\Http\Middleware;

use Closure;

class AdminAuth
{

    public function handle($request, Closure $next)
    {



        $sess =  $request->session();

        $accessLogin =  $request->is("usuario/sign-in");


        //Si no existe sesion Usuario y no accede al inicio de sesion
        if (!$sess->has("USUARIO")  &&  !$accessLogin)
            return redirect('usuario/sign-in');
        else {
            if ($sess->get("NIVEL")  ==  "SUPER")
                //permitir
                return $next($request);
                else 
                return redirect('modulo-caja');
        }
    }
}

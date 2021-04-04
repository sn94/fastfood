<?php

namespace App\Http\Middleware;

use Closure;

class Authenticate
{

    public function handle($request, Closure $next)
    {


       
        $sess=  $request->session();

        $accessLogin=  $request->is("usuario/sign-in");


        if( !  $sess->has("USUARIO")  &&  ! $accessLogin  )
        return redirect('usuario/sign-in');
        return $next($request);
    }


    
}

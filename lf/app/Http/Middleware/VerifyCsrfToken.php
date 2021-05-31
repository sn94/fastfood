<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'stock/buscar', 'stock/buscar/*', 'ficha-produccion/index', 'salida/index', 'remision-prod-terminados/index',
        'proveedores/buscar'
    ];
}

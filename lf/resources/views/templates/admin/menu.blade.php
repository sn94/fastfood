<?php


$adminOptionsMenu = [



    ["label" => "PROVEEDORES",  "link" =>  url('proveedores')],
    ["label" => "CLIENTES",  "link" => url('clientes')],
    [
        "label" => "DEPOSITO", "link" =>
        [

            ["label" => "SALIDAS DE PRODUCTOS Y MATERIA PRIMA", "link" =>  url('ficha-produccion/fichas/PENDIENTE/SALIDAS')],
            ["label" => "COMPRAS", "link" =>  url('compra/index')],
            ["label" => "STOCK",  "link" =>  url('stock')]

        ],
    ],

    [
        "label" => "COCINA", "link" =>
        [
            ["label" => "FICHA DE PRODUCCIÓN", "link" => url('ficha-produccion')],
            ["label" => "REMISIÓN DE PRODUCTOS TERMINADOS", "link" => url('ficha-produccion/fichas/DESPACHADO/TERMINADOS')],
            ["label" => "NOTA DE RESIDUOS",  "link" => url('ficha-produccion/fichas/DESPACHADO/RESIDUOS')]

        ],
    ],

    ["label" => "SESIONES",  "link" => url("sesiones")],


    [
        "label" => "AUXILIARES", "link" =>
        [
            ["label" => "USUARIOS", "link" => url('usuario')],
            //  ["label" => "NIVELES", "link" =>  url('niveles') ],
            ["label" => "CARGOS",  "link" => url('cargo')],
            ["label" => "TURNOS",  "link" => url('turno')],
            ["label" => "SUCURSALES",  "link" => url('sucursal')],
            ["label" => "FAMILIA DE PRODUCTOS",  "link" =>  url('familia')],
            ["label" => "PARÁMETROS",  "link" => url('parametros')],
            ["label" => "CIUDADES",  "link" => url('ciudades')],
            ["label" => "UNID. DE MEDIDA",  "link" => url('medidas')],
            ["label" => "CAJAS",  "link" => url('caja')]
        ],
    ],

    [
        "label" => "REPORTES", "link" =>
        [
            ["label" => "STOCK", "link" =>   url("stock/filtrar")  ],
            ["label" => "COMPRAS", "link" => url("compra/filtrar")],
            ["label" => "VENTAS", "link" => "#"]
        ],
    ],

    ["label" => "SALIR",  "link" =>  url('usuario/sign-out')],

    ["label" => "CAJA",  "link" =>  url('modulo-caja')]
];


if (session("MATRIZ") ==  "S")
    array_push($adminOptionsMenu[2]["link"],  ["label" => "PEDIDOS RECIBIDOS",  "link" =>  url('pedidos/recibidos')]);


?>


<x-fast-food-navbar :links="$adminOptionsMenu" />
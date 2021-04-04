<?php


$adminOptionsMenu = [



    ["label" => "PROVEEDORES",  "link" =>  url('proveedores')],
    ["label" => "CLIENTES",  "link" => url('clientes')],
    [
        "label" => "DEPOSITO", "link" =>
        [

            ["label" => "SALIDAS DE PRODUCTOS Y MATERIA PRIMA", "link" =>  url('deposito/fichas-de-produccion/PENDIENTE/SALIDAS')],
            ["label" => "COMPRAS", "link" =>  url('compra/index')],
            ["label" => "STOCK",  "link" =>  url('stock')]

        ],
    ],

    [
        "label" => "COCINA", "link" =>
        [
            ["label" => "FICHA DE PRODUCCIÓN", "link" => url('deposito/ficha-produccion')],
            ["label" => "REMISIÓN DE PRODUCTOS TERMINADOS", "link" => url('deposito/fichas-de-produccion/DESPACHADO/TERMINADOS')],
            ["label" => "NOTA DE RESIDUOS",  "link" => url('deposito/fichas-de-produccion/DESPACHADO/RESIDUOS')]

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
            ["label" => "STOCK", "link" =>  "#",
            /*[
                ["label" => "LISTA DE PRECIOS",  "link" => "#"],
                ["label" => "RANKING DE PRODUCTOS MÁS VENDIDOS",  "link" => "#"],
                ["label" => "COMPARATIVO DE VENTAS POR PRODUCTOS ",  "link" => "#"],
                ["label" => "LISTADO DE STOCK VALORADO REAL ",  "link" => "#"],
                ["label" => "LISTADO DE STOCK BAJO EL MINIMO ",  "link" => "#"]
                ]* */
            ],
            ["label" => "COMPRAS", "link" => url("compra/filtrar")],
            ["label" => "TOTALES POR SESIÓN Y SUCURSALES", "link" => "#"],
            ["label" => "STOCK TOTAL POR SUCURSALES",  "link" => "#"],
            ["label" => "PEDIDOS POR FECHAS Y POR SUCURSALES",  "link" => "#"],
            ["label" => "CONSUMO DEL PERSONAL POR MES",  "link" =>  "#"]
        ],
    ],

    ["label" => "SALIR",  "link" =>  url('usuario/sign-out')],

    ["label" => "CAJA",  "link" =>  url('modulo-caja')]
];


if (session("MATRIZ") ==  "S")
    array_push($adminOptionsMenu[2]["link"],  ["label" => "PEDIDOS RECIBIDOS",  "link" =>  url('pedidos/recibidos')]);


?>


<x-fast-food-navbar :links="$adminOptionsMenu" />
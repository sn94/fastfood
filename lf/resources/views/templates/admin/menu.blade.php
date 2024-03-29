<?php
 
 
$funcionDelivery= Illuminate\Support\Facades\Config::get("app.my_config.funciones.delivery");
 
 
$adminOptionsMenu = [



    ["label" => "PROVEEDORES",  "link" =>  url('proveedores')],
    ["label" => "CLIENTES",  "link" => url('clientes')],
    [
        "label" => "DEPOSITO", "link" =>
        [

            ["label" => "PEDIDOS DE SUCURSALES", "link" =>  url('pedidos/sucursales')],
            ["label" => "RECEPCIÓN DE PRODUCTOS ELABORADOS", "link" =>  url('remision-prod-terminados/index/confirmar')],
            ["label" => "SALIDAS DE PRODUCTOS Y MATERIA PRIMA", "link" =>  url('salida/index')],
            ["label" => "COMPRAS", "link" =>  url('compra/index')],
            ["label" => "STOCK",  "link" =>  url('stock')]

        ],
    ],

    [
        "label" => "COCINA", "link" =>
        [
            ["label" => "FICHA DE PRODUCCIÓN", "link" => url('ficha-produccion/index')],
            ["label" => "REMISIÓN DE PRODUCTOS TERMINADOS", "link" => url('remision-prod-terminados/index')],
            ["label" => "NOTA DE RESIDUOS",  "link" => url('nota-residuos/index')]

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
            ["label" => "CAJAS",  "link" => url('caja')],
            ["label" => "ORIGEN DE VENTA",  "link" => url('origen-venta')]
        ],
    ],

    [
        "label" => "REPORTES", "link" =>
        [
            ["label" => "STOCK", "link" =>   url("stock/filtrar")],
            ["label" => "COMPRAS ", "link" => url("compra/filtrar")],
            ["label" => "VENTAS", "link" =>  url("ventas/filtrar")]
        ],
    ],

    ["label" => "SALIR",  "link" =>  url('usuario/sign-out')],

    ["label" => "CAJA",  "link" =>  url('modulo-caja')]
];




//Links condicionales
if (session("MATRIZ") ==  "S")
    array_push($adminOptionsMenu[2]["link"],  ["label" => "PEDIDOS RECIBIDOS",  "link" =>  url('pedidos/recibidos')]);



if ($funcionDelivery == "S") {
    $adminOptionsMenu[5]['link'][] =  ["label" => "SERVICIOS",  "link" => url('servicios')];
}




?>


<x-fast-food-navbar :links="$adminOptionsMenu" />
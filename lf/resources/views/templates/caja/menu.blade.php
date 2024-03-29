<?php

//Condicional para sesiones


$sesiones_links = [];
//Existe una sesion de usuario
if (session()->has("SESION"))
  $sesiones_links = [["label" => "CERRAR", "link" => url("sesiones/cerrar")],  ["label" => "MIS SESIONES", "link" => url("sesiones?m=c")]];
else
  $sesiones_links = [["label" => "CREAR", "link" => url("sesiones/create")],  ["label" => "MIS SESIONES", "link" => url("sesiones?m=c")]]; //mandar a crear una


$cajaOptionsMenu = [

  [
    "label" => "VENTAS", "link" =>
    [
      ["label" => "NUEVA VENTA", "link" => url('ventas')],
      ["label" => "VENTAS DIARIAS", "link" => url('ventas/index')]
    ],
  ],

  ["label" => "CLIENTES",  "link" => url('clientes?m=c')],
  ["label" => "STOCK",  "link" => url("stock?m=c")],
  ["label" => "SESIÓN",  "link" => $sesiones_links],
  ["label" => "PEDIDOS",   "link" =>  [ 
      ["label" => "NUEVO", "link" =>  url("pedidos/unidades-vendidas")],
      ["label" => "PEDIDOS REALIZADOS", "link" =>  url("pedidos/realizados")]
  ] ],
  ["label" => "COMPRAS",  "link" => url("compra/index?m=c")],
  [
    "label" => "REPORTES", "link" =>
    [
      ["label" => "STOCK", "link" =>   url("stock/filtrar?m=c")],
      ["label" => "COMPRAS", "link" => url("compra/filtrar?m=c")],
      ["label" => "VENTAS", "link" =>  url("ventas/filtrar?m=c")]
    ],
  ],
  ["label" => "SALIR",  "link" =>  url('usuario/sign-out')]


];

//permisor super
if (session("NIVEL") == "SUPER" ||  session("NIVEL") == "GOD")
  array_push($cajaOptionsMenu,  ["label" => "ADMIN.",  "link" => url('modulo-administrativo')]);


?>


<x-fast-food-navbar :links="$cajaOptionsMenu" />
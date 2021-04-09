<?php

use App\Helpers\Utilidades;


$SESION =  isset($datalist) ?  $datalist['SESION'] :  $SESION;
$TICKETS =  isset($datalist) ?  $datalist['TICKETS'] :  $TICKETS;
$TOTALES =  isset($datalist) ?  $datalist['TOTALES'] :  $TOTALES;
$ANULADOS =  isset($datalist) ?  $datalist['ANULADOS'] :  $ANULADOS;
$VENDIDOS =  isset($datalist) ?  $datalist['VENDIDOS'] :  $VENDIDOS;
?>

<style>
  *{
font-family: Arial, Helvetica, sans-serif;
  }
  .text-center {
    text-align: center;
  }

  .text-end {
    text-align: right;
  }

 #tabla1  thead tr th:nth-child(4),
 #tabla1  tbody tr td:nth-child( 4),
 #tabla2  thead tr th:nth-child(4),
 #tabla2  tbody tr td:nth-child( 4)
 {

width: 150px;
  }

  #tabla0  tr th, #tabla0  tr td{
    font-size: 12px;
  }

  #tabla1 thead tr th,#tabla1 tbody tr td, #tabla1 tfoot tr td,
  #tabla2 thead tr th,#tabla2 tbody tr td, #tabla2 tfoot tr td,
  #tabla3 thead tr th,#tabla3 tbody tr td, #tabla3 tfoot tr td{ 
    font-size: 12px;
  }


  table thead tr th {
    border-bottom: 1px solid black;
  }
  
</style> 

 @include("sesiones.arqueo.form")
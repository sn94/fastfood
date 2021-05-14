<?php

use App\Helpers\Utilidades;

$VENTAS =   isset($datalist) ? $datalist : $VENTAS;
?>

@if( isset( $print_mode ))
@include("templates.print_report")
@else
<style>
    /** Estilos generales  */
    table thead tr th,
    table tbody tr td,
    table tfoot tr td {
        padding: 0px !important;
        padding-left: 1px !important;
        padding-right: 1px;
    }

    table {
        font-size: 13px;
        font-weight: 600;
    }
</style>
@endif



@if( isset( $titulo ))
<h4 style="text-align: center;">{{$titulo}}</h4>
@endif


<table class="table table-hover table-striped bg-warning">
    <thead class="thead-dark">
        <tr>

            <th>N° LOCAL</th>
            <th>LOCAL</th> 
            <th>DESCRIPCIÓN</th>
            <th>TIPO</th>
            <th>FAMILIA</th>
            <th class="text-center" >UNID.VENDIDAS</th>
        </tr>
    </thead>

    <tbody class="text-dark">

        @foreach( $VENTAS as $ven)

        <tr>
            <td>{{$ven->SUCURSAL_ID}} </td>
            <td>{{$ven->SUCURSAL_NOMBRE}} </td> 
            <td>{{$ven->DESCRIPCION}} </td>
            <td>{{ $ven->TIPO == "MP" ? "MATERIA PRIMA" : ( $ven->TIPO == "PE" ? "ELABORADO"  :  ( $ven->TIPO == "PP"  ? "PARA VENTA"   :  "MOBILIARIO Y OTROS")   )    }}</td>
            <td>{{$ven->FAMILIA}} </td>
            <td class="text-center"> {{$ven->NUMERO_VENTAS}}</td>
        </tr>
        @endforeach


    </tbody>
</table>

@if( method_exists($VENTAS, "links"))
{{ $VENTAS->links('vendor.pagination.default') }}
@if( sizeof( $VENTAS ) == 0)
<p class="text-center p-2 bg-warning text-dark">Sin registros</p>
@endif
@endif
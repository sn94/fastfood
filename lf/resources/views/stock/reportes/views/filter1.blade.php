<?php

use App\Helpers\Utilidades;

$STOCK =   isset($datalist) ? $datalist : $STOCK;
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


<table class="table table-hover table-striped fast-food-table">
    <thead class="thead-dark">
        <tr>

            <th>N° LOCAL</th>
            <th>LOCAL</th>
            <th>Descripción</th>
            <th>TIPO</th>
            <th>N° PEDIDOS</th>
        </tr>
    </thead>

    <tbody class="text-dark">

        @foreach( $STOCK as $ven)

        <tr>
            <td>{{$ven->SUCURSAL_ID}} </td>
            <td>{{$ven->SUCURSAL_NOMBRE}} </td>
            <td>{{$ven->DESCRIPCION}} </td>
           
            <td>
            @php 
            $TipoStock=$ven->TIPO;
            @endphp
            <x-tipo-stock-chooser :value="$TipoStock" style="border: none;" readonly="S" atributos="disabled" />
            </td>
            
            <td> {{$ven->NUMERO_PEDIDOS}}</td>
        </tr>
        @endforeach


    </tbody>
</table>

@if( method_exists($STOCK, "links"))
{{ $STOCK->links('vendor.pagination.default') }}
@if( sizeof( $STOCK ) == 0)
<p class="text-center p-2 fast-food-table text-dark">Sin registros</p>
@endif
@endif
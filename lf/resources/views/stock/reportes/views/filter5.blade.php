<?php

use App\Helpers\Utilidades;

$STOCK =   isset($datalist) ? $datalist : $STOCK;
?>


@if( isset($print_mode) )
@include("templates.print_report")
@else
<style>
    table {
        font-size: 13px;
        font-weight: 600;
    }
</style>
@endif


@if( isset( $titulo ))
<h4 style="text-align: center;">{{$titulo}}</h4>
@endif



<style>
    table thead tr th,
    table tbody tr td,
    table tfoot tr td {
        padding: 0px !important;
        padding-left: 2px !important;
        padding-right: 2px;
    }


    table thead tr th:nth-child(1),
    table tbody tr td:nth-child(1),
    table tfoot tr td:nth-child(1) {
        width: 40px;
    }
    table thead tr th:nth-child(2),
    table tbody tr td:nth-child(2),
    table tfoot tr td:nth-child(2){
        width: 100px;
    }
   
    table thead tr th:nth-child(3),
    table tbody tr td:nth-child(3),
    table tfoot tr td:nth-child(3)  {
        width: 40px !important;
        text-align: left !important;
    }

    table thead tr th:nth-child(4),
    table tbody tr td:nth-child(4),
    table tfoot tr td:nth-child(4) {
        width: 60px !important;
        text-align: left !important;
    }
 

    table thead tr th:nth-child(5),
    table tbody tr td:nth-child(5),
    table tfoot tr td:nth-child(5){
        width: 100px;
    }
    table thead tr th:nth-child(6),
    table tbody tr td:nth-child(6),
    table tfoot tr td:nth-child(6) {
        width: 50px;
    }


    table thead tr th:nth-child(7),
    table tbody tr td:nth-child(7),
    table tfoot tr td:nth-child(7) {
        width: 50px;
    }
    table thead tr th:nth-child(8),
    table tbody tr td:nth-child(8),
    table tfoot tr td:nth-child(8) ,
    table thead tr th:nth-child(9),
    table tbody tr td:nth-child(9),
    table tfoot tr td:nth-child(9)  {
        width: 70px;
    }
</style>








<table class="table table-hover table-striped fast-food-table">
    <thead class="thead-dark">

        <tr>
            <th class="text-center">N° LOCAL</th>
            <th>LOCAL</th>
            <th class="text-center">FECHA</th>
            <th class="text-center">SALIDA N°</th>
            <th style="text-align: left;">Descripción</th>
            <th>TIPO</th>
          
            <th class="text-center">CANTIDAD</th>
            <th class="text-center">MEDIDA</th>
            <th class="text-center">DESTINO</th>


        </tr>
    </thead>

    <tbody class="text-dark">

        @foreach( $STOCK as $ven)
        <tr>
            <td class="text-center"> {{ $ven->SUCURSAL }}</td>
            <td> {{ $ven->SUCURSAL_NOMBRE }}</td>
            <td class="text-center"> {{ $ven->FECHA }}</td>
            <td class="text-center"> {{ $ven->SALIDA_ID }}</td>
           
            <td>{{ $ven->DESCRIPCION}} </td>
            <td>
            @php 
            $TipoStock=$ven->TIPO;
            @endphp
            <x-tipo-stock-chooser :value="$TipoStock" style="border: none;" readonly="S" atributos="disabled" />
            </td>

            
            <td class="text-center"> {{$ven->CANTIDAD}}</td>
            <td class="text-center"> {{ $ven->MEDIDA }}</td>
            <td class="text-center"> {{ $ven->DESTINO }}</td>

        </tr>
        @endforeach


    </tbody>
</table>


<x-pretty-paginator :datos="$STOCK" callback="filtrar" />
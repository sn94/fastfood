<?php

use App\Helpers\Utilidades;
$STOCK =   isset($datalist) ? $datalist : $STOCK;
?>


@if( isset($print_mode) )
@include("templates.print_report")
@else 
<style>
    table{
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
    table tfoot tr td:nth-child(1){
width: 60px;
    }
    table thead tr th:nth-child(2),
    table tbody tr td:nth-child(2),
    table tfoot tr td:nth-child(2),
    table thead tr th:nth-child(3),
    table tbody tr td:nth-child(3),
    table tfoot tr td:nth-child(3) {
        width: 150px !important;
        text-align: left !important;
    }

    table thead tr th:nth-child(4),
    table tbody tr td:nth-child(4),
    table tfoot tr td:nth-child(4){
width: 100px;
    }

    table thead tr th:nth-child(5),
    table tbody tr td:nth-child(5),
    table tfoot tr td:nth-child(5),
    table thead tr th:nth-child(6),
    table tbody tr td:nth-child(6),
    table tfoot tr td:nth-child(6){
width: 80px;
    }

    table thead tr th:nth-child(7),
    table tbody tr td:nth-child(7),
    table tfoot tr td:nth-child(7){
width: 50px;
    }

  
</style>








<table class="table table-hover table-striped fast-food-table">
    <thead class="thead-dark">

        <tr>
            <th class="text-center">N° LOCAL</th>
            <th>LOCAL</th>
            <th style="text-align: left;">Descripción</th>
            <th>TIPO</th>
            <th class="text-center">FAMILIA</th>
            <th class="text-center" >STOCK</th>
            <th class="text-center">MEDIDA</th>

        </tr>
    </thead>

    <tbody class="text-dark">

        @foreach( $STOCK as $ven)
        <tr>
            <td class="text-center"> {{ $ven->SUCURSAL }}</td>
            <td> {{ $ven->SUCURSAL_NOMBRE }}</td>
            <td>{{ $ven->DESCRIPCION}} </td>
            <td>
            @php 
            $TipoStock=$ven->TIPO;
            @endphp
            <x-tipo-stock-chooser :value="$TipoStock" style="border: none;" readonly="S" atributos="disabled" />
            </td>


            <td class="text-center"> {{ $ven->FAMILIA }}</td>
            <td class="text-center"> {{$ven->CANTIDAD}}</td>
            <td class="text-center"> {{ $ven->MEDIDA }}</td>

        </tr>
        @endforeach


    </tbody>
</table>


<x-pretty-paginator   :datos="$STOCK"  callback="filtrar"/>



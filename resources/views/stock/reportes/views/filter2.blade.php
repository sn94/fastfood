<?php
// Residuos 
// de 
//ingredientes 


use App\Helpers\Utilidades;

$STOCK =   isset($datalist) ? $datalist : $STOCK;
?>


<style>

  /** Estilos generales  */
  table thead tr th,
    table tbody tr td,
    table tfoot tr td {
        padding: 0px !important;
        padding-left: 1px !important;
        padding-right: 1px;
    }


    table thead tr th:nth-child(1),
    table tbody tr td:nth-child(1),
    table tfoot tr td:nth-child(1) {
        width: 70px !important;
    }

    table thead tr th:nth-child(2),
    table tbody tr td:nth-child(2),
    table tfoot tr td:nth-child(2) {
        width: 170px !important;
    }

    table thead tr th:nth-child(3),
    table tbody tr td:nth-child(3),
    table tfoot tr td:nth-child(3) {
        width: 170px !important;
    }

    table thead tr th:nth-child(4),
    table tbody tr td:nth-child(4),
    table tfoot tr td:nth-child(4) {
        width: 80px !important;
    }

    table thead tr th:nth-child(5),
    table tbody tr td:nth-child(5),
    table tfoot tr td:nth-child(5),
    table thead tr th:nth-child(6),
    table tbody tr td:nth-child(6),
    table tfoot tr td:nth-child(6) {
        width: 50px !important;
    }



    table thead tr th:nth-child(7),
    table tbody tr td:nth-child(7),
    table tfoot tr td:nth-child(7) {
        width: 40px;
    }

 
</style>

@if(  isset($print_mode) ) 
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


<table class="table table-hover table-striped fast-food-table">
    <thead class="thead-dark">

        <tr>
            <th class="text-center">N° LOCAL</th>
            <th>LOCAL</th>
            <th style="text-align: left;">Descripción</th>
            <th>TIPO</th>
            <th class="text-center">REGISTROS</th>
            <th class="text-end">CANTIDAD</th>
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
            
            <td class="text-center"> {{ $ven->NUMERO_RESIDUOS }}</td>
            <td class="text-end"> {{ Utilidades::number_f($ven->TOTAL_RESIDUOS)}}</td>
            <td class="text-center"> {{$ven->MEDIDA}} </td>
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
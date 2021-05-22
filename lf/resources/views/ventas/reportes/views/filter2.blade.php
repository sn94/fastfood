<?php
// Residuos 
// de 
//ingredientes 


use App\Helpers\Utilidades;

$VENTAS =   isset($datalist) ? $datalist : $VENTAS;
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
        width: 130px !important;
    }

    table thead tr th:nth-child(3),
    table tbody tr td:nth-child(3),
    table tfoot tr td:nth-child(3) {
        width: 85px !important;
    }

    table thead tr th:nth-child(4),
    table tbody tr td:nth-child(4),
    table tfoot tr td:nth-child(4) {
        width: 40px !important;
    }

    table thead tr th:nth-child(5),
    table tbody tr td:nth-child(5),
    table tfoot tr td:nth-child(5)  {
        width: 150px !important;
    }


    table thead tr th:nth-child(6),
    table tbody tr td:nth-child(6),
    table tfoot tr td:nth-child(6) {
        width: 60px !important;
    }

    table thead tr th:nth-child(7),
    table tbody tr td:nth-child(7),
    table tfoot tr td:nth-child(7) {
        width: 60px;
    }
    table thead tr th:nth-child(8),
    table tbody tr td:nth-child(8),
    table tfoot tr td:nth-child(8) {
        width: 50px;
    }
</style>

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


<table class="table table-hover table-striped bg-warning">
    <thead class="thead-dark">

        <tr>
            <th class="text-center">N° LOCAL</th>
            <th>LOCAL</th>
            <th style="text-align: left;">FACTURA N°</th>
            <th style="text-align: left;">CAJERO</th>
            <th style="text-align: left;">FECHA</th>
            <th>CLIENTE</th>
            <th class="text-center">TIPO PAGO</th>
            <th class="text-end">TOTAL</th>
            <th class="text-center">ESTADO</th>

        </tr>
    </thead>

    <tbody class="text-dark">

        @foreach( $VENTAS as $ven)
 
        <tr>
            <td class="text-center"> {{ $ven->SUCURSAL_ID }}</td>
            <td> {{ $ven->SUCURSAL_NOMBRE }}</td>
            <td class="text-center">{{ $ven->FACTURA}} </td>
            <td class="text-end"> {{ is_null( $ven->cajero) ? '' :   $ven->cajero  }} </td>
            <td>{{ $ven->FECHA->format('d/m/Y')  }}</td>
            <td>{{$ven->cliente_nom}}</td>
            <td class="text-center"> {{ $ven->FORMA_PAGO }}</td>
            <td class="text-end"> {{ $ven->TOTAL}}</td>
            <td class="text-center"> {{$ven->ESTADO}} </td>
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
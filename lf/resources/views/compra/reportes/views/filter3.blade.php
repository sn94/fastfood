<?php

use App\Helpers\Utilidades;

$COMPRAS =   isset($datalist) ? $datalist : $COMPRAS;
?>
@if( isset($datalist) )

<style>
    table,
    h4 {
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
    }
</style>
<h4 style="text-align: center;">Comparativos de compras por sucursal</h4>
@endif


<style>
    table thead tr th,
    table tbody tr td,
    table tfoot tr td {
        padding: 0px !important;
        padding-left: 2px !important;
        padding-right: 2px;
    }

    .text-end {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }
</style>








<table class="table table-hover table-striped bg-warning">
    <thead class="thead-dark">

        <th>SUC. N°</th>
        <th>SUCURSAL</th>
        <th>N° COMPRAS</th>
        <th class="text-end">IMPORTE</th>
    </thead>

    <tbody class="text-dark">

        @foreach( $COMPRAS as $ven)
 
        <tr>

            <td>{{ $ven->SUCURSAL}} </td>
            <td> {{ $ven->sucursal->DESCRIPCION }} </td>
            <td> {{ $ven->NRO_COMPRAS}}</td>
            <td class="text-end">{{ $ven->TOTAL_COMPRAS   }}</td>
            
        </tr>
        @endforeach


    </tbody>
</table>

@if( method_exists($COMPRAS, "links"))
{{ $COMPRAS->links('vendor.pagination.default') }}
@endif
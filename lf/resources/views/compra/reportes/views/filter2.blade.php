<?php

use App\Helpers\Utilidades;

$COMPRAS =   isset($datalist) ? $datalist : $COMPRAS;
?>
@if( isset($datalist) )

<style>
table, h4{
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
}
</style>
<h4 style="text-align: center;">Productos más comprados</h4>
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

        <th>CÓDIGO</th>
        <th>BARCODE</th>
        <th>DESCRIPCIÓN</th>
        <th>TIPO</th>
        <th class="text-center">N° COMPRAS</th>
        <th>CANTIDAD</th>
    </thead>

    <tbody class="text-dark">

        @foreach( $COMPRAS as $ven)

        @php
        $codigo=  $ven->CODIGO;
        $barcode= $ven->BARCODE;
        $tipoStock= $ven->TIPO_PRODUCTO == "MP" ? "MATERIA PRIMA" : ( $ven->TIPO_PRODUCTO == "PE" ? "ELABORADO" : ( $ven->TIPO_PRODUCTO == "PP" ? "PARA VENTA" : "MOBILIARIO") ) ;

        @endphp
        <tr>

            <td>{{ $codigo}} </td>
            <td> {{ $barcode}} </td>
            <td> {{ is_null($ven)  ? '***' : $ven->DESCRIPCION}}</td>
            <td>{{ $tipoStock   }}</td>
            <td class="text-end fw-bold text-center">{{ $ven->NRO_COMPRAS}}</td>
            <td style="display: flex;justify-content: space-around;"> 
            {{ $ven->CANTIDAD}} 
             <span class="badge bg-success" > {{ $ven->UNI_MEDIDA}}</span> </td>
        </tr>
        @endforeach


    </tbody>
</table>

@if( method_exists($COMPRAS, "links"))
{{ $COMPRAS->links('vendor.pagination.default') }}
@endif
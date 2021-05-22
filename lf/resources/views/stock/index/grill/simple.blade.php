<?php

use App\Helpers\Utilidades;

?>
<style>
    h4 {
        text-align: center;
    }

    table {

        font-family: Arial, Helvetica, sans-serif;
    }

    table thead tr th,
    table tbody tr td {
        padding: 0px;

    }



    table thead tr th {
        font-size: 11px;
        border-bottom: 1px solid black;
    }

    table tbody tr td,
    table tbody tr th {
        font-size: 10px;
    }
</style>





<h4>Stock</h4>
<table>

    <thead>
        <tr>

        <th>SUC.</th>
            <th>CÓDIGO</th>
            <th>CÓD. BARRA</th>
            <th>DESCRIPCIÓN</th>
            <th class="text-end">STOCK TOT.</th>
            <th class="text-end">VENTA</th>
        </tr>
    </thead>
    <tbody>

        @foreach( $datalist as $prov )

        @php
        $ESTADO_STOCK= "table-success";
        $MENSAJE_STOCK= "";

        $STOCK_ACTUAL= $prov->ENTRADAS + $prov->ENTRADA_PE + $prov->ENTRADA_RESIDUO -($prov->SALIDAS + $prov->SALIDA_VENTA);


        if( $STOCK_ACTUAL <= 0): $ESTADO_STOCK="table-danger" ; $MENSAJE_STOCK="(Sin stock)" ; endif; @endphp <tr class="{{$ESTADO_STOCK}}">

        <td>{{$prov->SUCURSAL}}</td>
            <td  style="text-align: center;">{{($prov->CODIGO=='' ? '':  $prov->CODIGO)}}</td>
            <td  style="text-align: center;">{{($prov->BARCODE=='' ? '':  $prov->BARCODE)}}</td>
            <td>{{$prov->DESCRIPCION}} <span style="color:red;font-weight: 600;">{{$MENSAJE_STOCK}}</span> </td>
            <td style="text-align: right;" > {{ $STOCK_ACTUAL}} {{$prov->unidad_medida->DESCRIPCION}}</td>
            <td style="text-align: right;">{{ Utilidades::number_f( $prov->PVENTA) }}</td>

            </tr>

            @endforeach
    </tbody>
</table>
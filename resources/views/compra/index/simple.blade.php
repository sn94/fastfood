<style>
    table thead tr th,
    table tbody tr td,
    table tfoot tr td {
        padding: 0px !important;
        padding-left: 2px !important;
        padding-right: 2px;
        font-size: 13px;
    }

    .text-end{
        text-align: right;
    }
    .text-center{
        text-align: center;
    }
</style>


<?php

use App\Helpers\Utilidades;

$COMPRAS = $datalist;
?>

<table class="table table-hover table-striped ">
    <thead class="thead-dark">
        <tr>
        <th>SUCURSAL</th>
            <th class="text-center">N° FACTURA</th>
            <th>FECHA</th>
            <th>HORA</th>
            <th>PROVEEDOR</th>
            <th>FORMA-PAGO</th>
            <th class="text-end">TOTAL</th>
        </tr>
    </thead>

    <tbody class="text-dark">

        @foreach( $COMPRAS as $ven)

        <tr>
        <th>{{$ven->SUCURSAL}}</th>
            <td class="text-center" >{{$ven->REGNRO}}</td>
            <td>{{ Utilidades::fecha_f($ven->FECHA) }}</td>
            <td>{{ date("H:i:s", strtotime(  $ven->created_at )   )}}</td>
            <td>{{ is_null( $ven->proveedor) ? '' :  ($ven->proveedor->NOMBRE . "(". $ven->proveedor->CEDULA_RUC . ")") }}</td>
            <td>{{$ven->FORMA_PAGO}}</td>
            <td class="text-end">{{ Utilidades::number_f( $ven->TOTAL() ) }}</td>
        </tr>
        @endforeach


    </tbody>
</table>
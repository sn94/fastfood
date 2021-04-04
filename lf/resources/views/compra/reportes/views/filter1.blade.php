<?php
use App\Helpers\Utilidades;
$COMPRAS =   isset($datalist) ? $datalist : $COMPRAS;
?>




<style>
   table thead tr th{
       border-bottom: 1px solid black;
   }
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
</style>

@if( isset($datalist) )

<style>
table, h4{
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
}
</style>
<h4 style="text-align: center;">Compras por producto y por proveedor</h4>
@endif



<table class="table table-hover table-striped bg-warning">
    <thead class="thead-dark">
        <tr>

            <th>SUC.</th>
            <th>COMPRA</th>
            <th>FECHA</th>
            <th>DESCRIPCIÓN</th>
            <th>TIPO</th>
            <th>P.VENTA</th>
            <th>P.COSTO</th>
            <th>PROVEEDOR</th>
        </tr>
    </thead>

    <tbody class="text-dark">

        @foreach( $COMPRAS as $ven)

        <tr>

            <td>{{$ven->SUCURSAL}} </td>
            <td>{{$ven->COMPRA_ID}} </td>
            <td>{{$ven->FECHA->format('d/m/Y')}}</td>
            <td> {{$ven->DESCRIPCION}}</td>
            <td>{{ $ven->TIPO_PRODUCTO == "MP" ? "MATERIA PRIMA" : ( $ven->TIPO_PRODUCTO == "PE" ? "ELABORADO"  :  ( $ven->TIPO_PRODUCTO == "PP"  ? "PARA VENTA"   :  "MOBILIARIO")   )    }}</td>
            <td class="text-end fw-bold">{{ $ven->PVENTA == "" ? "****" :  $ven->PVENTA}}</td>
            <td class="text-end fw-bold">{{ $ven->P_UNITARIO}}</td>

            <td>{{ $ven->PROVEEDOR_NOM}}</td>
        </tr>
        @endforeach


    </tbody>
</table>

@if( method_exists($COMPRAS, "links"))
{{ $COMPRAS->links('vendor.pagination.default') }}
@endif
<?php
use App\Helpers\Utilidades;
$STOCK =   isset($datalist) ? $datalist : $STOCK;
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
        font-size: 12px;
        
    }

    .text-end {
        text-align: right;
    }
</style>

@if( isset($datalist) )

<style>
table, h4{
    font-family: Arial, Helvetica, sans-serif;
}
</style>
<h4 style="text-align: center;">Compras por producto y por proveedor</h4>
@endif



<table class="table table-hover table-striped bg-warning">
    <thead class="thead-dark">
        <tr>

        <th>SUC. ID.</th>
            <th>SUCURSAL</th>
            <th>DESCRIPCIÓN</th>
            <th>TIPO</th> 
            <th>N° PEDIDOS</th> 
        </tr>
    </thead>

    <tbody class="text-dark">

        @foreach( $STOCK as $ven)

        <tr>
        <td>{{$ven->SUCURSAL_ID}} </td>
            <td>{{$ven->SUCURSAL}} </td>
            <td>{{$ven->DESCRIPCION}} </td>
            <td>{{$ven->TIPO }}</td>
            <td> {{$ven->NUMERO_PEDIDOS}}</td> 
        </tr>
        @endforeach


    </tbody>
</table>

@if( method_exists($STOCK, "links"))
{{ $STOCK->links('vendor.pagination.default') }}
@if(  sizeof( $STOCK ) == 0)
<p class="text-center p-2 bg-warning text-dark">Sin registros</p>
@endif
@endif


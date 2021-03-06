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
    font-family: Arial, Helvetica, sans-serif;
}
table{
    font-size: 12px;
}
</style>


<h4 style="text-align: center;">Compras por producto y por proveedor</h4>
@endif



<table class="table table-hover table-striped fast-food-table">
    <thead class="thead-dark">
        <tr>

            <th>SUC.</th>
            <th>COMPRA</th>
            <th>FECHA</th>
            <th>Descripción</th>
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
            <td>
            @php 
            $TipoStock=$ven->TIPO_PRODUCTO;
            @endphp
            <x-tipo-stock-chooser :value="$TipoStock" style="border: none;" readonly="S" atributos="disabled" />
            </td>

           
            <td class="text-end fw-bold">{{ $ven->PVENTA == "" ? '' :  $ven->PVENTA}}</td>
            <td class="text-end fw-bold">{{ $ven->P_UNITARIO}}</td>

            <td>{{ $ven->PROVEEDOR_NOM}}</td>
        </tr>
        @endforeach


    </tbody>
</table>

@if( method_exists($COMPRAS, "links"))
{{ $COMPRAS->links('vendor.pagination.default') }}
@if(  sizeof( $COMPRAS ) == 0)
<p class="text-center p-2 fast-food-table text-dark">Sin registros</p>
@endif
@endif


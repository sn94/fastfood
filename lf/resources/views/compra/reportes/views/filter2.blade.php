<?php

use App\Helpers\Utilidades;

$COMPRAS =   isset($datalist) ? $datalist : $COMPRAS;
?>


@if( isset($datalist) )

<style>
    table,
    h4 { 
        font-family: Arial, Helvetica, sans-serif;
    }
    table{
        font-size: 12px;
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

    table thead tr th:nth-child(1),
    table tbody tr td:nth-child(1),
    table tfoot tr td:nth-child(1),
    table thead tr th:nth-child(2),
    table tbody tr td:nth-child(2),
    table tfoot tr td:nth-child(2),
    table thead tr th:nth-child(3),
    table tbody tr td:nth-child(3),
    table tfoot tr td:nth-child(3) {
     width: 60px  !important;
     text-align: center !important;
    }

    table thead tr th:nth-child(4),
    table tbody tr td:nth-child(4),
    table tfoot tr td:nth-child(4) {
     width: 150px  !important;
     text-align: center !important;
    }

    table thead tr th:nth-child(5),
    table tbody tr td:nth-child(5),
    table tfoot tr td:nth-child(5),
    table thead tr th:nth-child(6),
    table tbody tr td:nth-child(6),
    table tfoot tr td:nth-child(6){
        width: 80px  !important;
     text-align: center !important;
    }


    table thead tr th:nth-child(7),
    table tbody tr td:nth-child(7),
    table tfoot tr td:nth-child(7){
        width: 60px  !important; 
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

        <tr>
            <th>SUCURSAL</th>
            <th>CÓDIGO</th>
            <th>BARCODE</th>
            <th>DESCRIPCIÓN</th>
            <th>TIPO</th>
            <th class="text-center">N° COMPRAS</th>
            <th class="text-end">CANTIDAD</th>
        </tr>
    </thead>

    <tbody class="text-dark">

        @foreach( $COMPRAS as $ven)

        @php
        $codigo= $ven->CODIGO;
        $barcode= $ven->BARCODE;

       
        @endphp
        <tr>

            <td> {{ $ven->SUCURSAL }}</td>
            <td>{{ $codigo}} </td>
            <td> {{ $barcode}} </td>
            <td> {{ is_null($ven)  ? '***' : $ven->DESCRIPCION}}</td>
            
            <td>
            @php 
            $TipoStock=$ven->TIPO_PRODUCTO;
            @endphp
            <x-tipo-stock-chooser :value="$TipoStock" style="border: none;" readonly="S" atributos="disabled" />
            </td>


            <td class="text-end fw-bold text-center">{{ $ven->NRO_COMPRAS}}</td>

            <td class="text-end"  >
 
               <b> {{ $ven->CANTIDAD}}</b>  {{ $ven->UNI_MEDIDA}}
            

            </td>
        </tr>
        @endforeach


    </tbody>
</table>

@if( method_exists($COMPRAS, "links"))
{{ $COMPRAS->links('vendor.pagination.default') }}
@if(  sizeof( $COMPRAS ) == 0)
<p class="text-center p-2 bg-warning text-dark">Sin registros</p>
@endif
@endif


<?php

use App\Helpers\Utilidades;

$STOCK =   isset($datalist) ? $datalist : $STOCK;
?>
@if( isset($datalist) )

<style>
    table,
    h4 { 
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
        font-size: 12px;
    }

     
    table thead tr th:nth-child(2),
    table tbody tr td:nth-child(2),
    table tfoot tr td:nth-child(2) {
     width: 150px  !important;
     text-align: left !important;
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
            <th style="text-align: left;">DESCRIPCIÓN</th>
            <th>TIPO</th>
            <th>N° NOTAS DE RES.</th>
             
        </tr>
    </thead>

    <tbody class="text-dark">

        @foreach( $STOCK as $ven)

          
        <tr>

            <td> {{ $ven->SUCURSAL }}</td>
            <td>{{ $ven->DESCRIPCION}} </td>
            <td> {{ $ven->TIPO}} </td>
            <td> {{$ven->NUMERO_RESIDUOS}}</td>
                
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


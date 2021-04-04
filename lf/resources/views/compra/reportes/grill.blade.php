<style>
   
    table thead tr th,
  table tbody tr td,
  table tfoot tr td {
    padding: 0px !important;
    padding-left: 2px !important;
    padding-right: 2px; 
  }


</style>

<?php

use App\Helpers\Utilidades;
 

//Plantilla 
$operacionUrl= ""; 

if (preg_match("/compra/", request()->path())) : 
    $operacionUrl=  url("compra"); 
    
elseif (preg_match("/compras-en-caja/", request()->path())) : 
    $operacionUrl=  url("compras-en-caja");
    
endif;
?>

<table class="table table-hover table-striped">
    <thead class="thead-dark">

        <th></th>
        <th></th>
        <th>N° FACTURA</th>
        <th>FECHA</th>
        <th>HORA</th>
        <th>PROVEEDOR</th>
       <th>FORMA-PAGO</th>
        <th class="text-end">TOTAL</th>
    </thead>

    <tbody class="text-dark">

        @foreach( $COMPRAS as $ven)

        <tr>

            <td> <a onclick="delete_row(event)" style="color: black;" href="{{ $operacionUrl.'/'.$ven->REGNRO}}"> <i class="fa fa-trash"></i></a></td>
            <td> <a  style="color: black;" href="{{$operacionUrl.'/'.$ven->REGNRO}}"> <i class="fa fa-edit"></i></a></td>
            <td>{{$ven->REGNRO}}</td>
            <td>{{ Utilidades::fecha_f($ven->FECHA) }}</td>
            <td>{{ date("H:i:s", strtotime(  $ven->created_at )   )}}</td>
            <td>{{ is_null( $ven->proveedor) ? '' :  ($ven->proveedor->NOMBRE . "(". $ven->proveedor->CEDULA_RUC . ")") }}</td>
            <td>{{$ven->FORMA_PAGO}}</td>
            <td class="text-end">{{  Utilidades::number_f( $ven->TOTAL() ) }}</td>
        </tr>
        @endforeach


    </tbody>
</table>


@if(  !is_array($COMPRAS) )
{{ $COMPRAS->links('vendor.pagination.default') }}
@endif
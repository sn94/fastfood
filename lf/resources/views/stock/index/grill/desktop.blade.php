<?php

use App\Helpers\Utilidades;

$MODULO_FLAG =    isset($_GET['m']) ? $_GET['m'] :  "";
$QUERY_FLAG =  $MODULO_FLAG == "c" ? "?m=c"  :  ""; 

?>

@if( session("NIVEL") == "SUPER"  ||  session("NIVEL") == "GOD" )
<style>
    table thead tr th:nth-child(1),
    table tbody tr td:nth-child(1) {
        padding-left: 2px;
        width: 3%;
    }

    table thead tr th:nth-child(2),
    table tbody tr td:nth-child(2) {
        width: 3%;
    }
</style>
@endif

<style>
    table thead tr th,
    table tbody tr td {
        padding: 0px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        padding-left: 3px !important;
        padding-right: 3px !important;
    }
</style>

<table class="table bg-warning bg-gradient    text-dark">

    <thead>
        <tr>
            @if( session("NIVEL") == "SUPER" ||  session("NIVEL") == "GOD" )
            <th></th>
            <th></th>
            @endif
            <th>CÓDIGO</th>
            <th>CÓD. BARRA</th>
            <th><a href="#" onclick="ordenarDescripcion('ASC')"> <img src="<?= url('assets/icons/up_icon.png') ?>" alt="Asc"></a> DESCRIPCIÓN
                <a href="#" onclick="ordenarDescripcion('DESC')"><img src="<?= url('assets/icons/down_icon.png') ?>"></a>
            </th>
            <th class="text-end">STOCK TOT.</th>
            <th class="text-end"><a href="#" onclick="ordenarPventa('ASC')"> <img src="<?= url('assets/icons/up_icon.png') ?>" alt="Asc"></a> VENTA
                <a href="#" onclick="ordenarPventa('DESC')"><img src="<?= url('assets/icons/down_icon.png') ?>"></a>
            </th>

            @if( $MODULO_FLAG == "c")
            <th class="text-end">PRECIO MITAD</th>
            <th class="text-center">VENTA MITAD</th>
            @endif
        </tr>
    </thead>

    <tbody>

        @foreach( $stock as $itemStock)
 

 @php
 $ESTADO_STOCK= "table-success";
 $MENSAJE_STOCK= "";

 $STOCK_ACTUAL= $itemStock->CANTIDAD;

 if( $STOCK_ACTUAL <= 0): $ESTADO_STOCK="table-danger" ; $MENSAJE_STOCK="(Sin stock)" ; endif; @endphp <tr class="{{$ESTADO_STOCK}}">

     @if( session("NIVEL") == "SUPER" || session("NIVEL") == "GOD" )
     <td>
         <a style="color: black;" href="{{url('stock/update').'/'.$itemStock->REGNRO}}"> <i class="fas fa-edit"></i></a>
     </td>
     <td>
         <a onclick="delete_row(event)" style="color: black;" href="{{url('stock').'/'.$itemStock->REGNRO}}"> <i class="fa fa-trash"></i></a>
     </td>
     @endif

     <td>{{($itemStock->CODIGO=='' ? '':  $itemStock->CODIGO)}}</td>
     <td>{{($itemStock->BARCODE=='' ? '':  $itemStock->BARCODE)}}</td>
     <td>{{$itemStock->DESCRIPCION}} <span style="color:red;font-weight: 600;">{{$MENSAJE_STOCK}}</span> </td>



     <td class="text-end"> {{ $STOCK_ACTUAL}}
         @if($MODULO_FLAG != "c")
         {{$itemStock->unidad_medida->DESCRIPCION}}
         @endif
     </td>
     <td class="text-end">{{ Utilidades::number_f( $itemStock->PVENTA) }}</td>
     @if( $MODULO_FLAG== "c")
     <td class="text-end">{{ Utilidades::number_f( $itemStock->PVENTA_MITAD) }}</td>
     <td class="text-center"> {{$itemStock->PRECIOS_VARIOS=="S" ? "SI" : "NO"}} </td>
     @endif

     </tr>
        @endforeach
    </tbody>

</table>
 
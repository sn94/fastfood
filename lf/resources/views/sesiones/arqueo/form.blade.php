<?php

use App\Helpers\Utilidades;


$SESION =  isset($datalist) ?  $datalist['SESION'] :  $SESION;
$TICKETS =  isset($datalist) ?  $datalist['TICKETS'] :  $TICKETS;
$TOTALES =  isset($datalist) ?  $datalist['TOTALES'] :  $TOTALES;
$ANULADOS =  isset($datalist) ?  $datalist['ANULADOS'] :  $ANULADOS;
$VENDIDOS =  isset($datalist) ?  $datalist['VENDIDOS'] :  $VENDIDOS;
?>

<style>
  .text-center {
    text-align: center;
  }

  .text-end {
    text-align: right;
  }

  table thead tr th,
  table tbody tr td,
  table tfoot tr td {
    padding: 0px !important;
    padding-left: 2px !important;
    padding-right: 2px;
  }


  table thead tr th {
    border-bottom: 1px solid black;
  }

.text-center{
  text-align: center;
}
  .text-end {
    text-align: right;
  }

  table.header th { 
    text-align: left;
  }
</style>

@if( ! request()->ajax())
<h4 class="text-center  ">Arqueo de caja</h4>
@endif 

<table id="tabla0" class="table fast-food-table bg-gradient   table-bordered header">
<tr >
    <th>Fecha de impresión:</th>
    <td>{{date('d/m/Y')}}</td>
    <th>Monto inicial:</th> 
    <td class="text-end">{{ Utilidades::number_f( $SESION->EFECTIVO_INI ) }} </td>
    <th>Ventas de la Sesión N°:</th>
    <td>{{$SESION->REGNRO}}</td>
  </tr>
 
  <tr>
    <th>Cajero/a:</th>
    <td>{{is_null($SESION->cajero) ?  '' :   $SESION->cajero->NOMBRES}}</td>
  </tr>
</table>

<table id="tabla1"   class="table fast-food-table bg-gradient   table-bordered">
  <thead>
    <tr>
      <th>Venta ID°</th>
      <th>Ticket</th>
      <th>Fecha</th>
      <th>Cliente</th>
      <th>Imp. Venta</th>
    </tr>
  </thead>
  <tbody>
    @foreach( $TICKETS as $ticket)
    <tr>
      <td>{{$ticket->REGNRO}}</td>
      <td>{{$ticket->REGNRO}}</td>
      <td>{{ is_null($ticket->FECHA) ? '':  $ticket->FECHA->format('d/m/Y')}}</td>
      <td>{{ is_null($ticket->cliente) ?  '' : $ticket->cliente->NOMBRE}}</td>
      <td class="text-end">{{ Utilidades::number_f( $ticket->TOTAL_VENTA + $ticket->COSTO_SERVICIO)}}</td>
    </tr>
    @endforeach
  </tbody>
  <tfoot>
    
    <tr>
      <td colspan="4">TOTAL EFECTIVO</td>
      <td class="text-end"> {{ Utilidades::number_f($TOTALES['EFECTIVO'])  }} </td>
    </tr>
    <tr>
      <td colspan="4">TOTAL TARJETA CRÉDITO/DÉBITO</td>
      <td class="text-end"> {{ Utilidades::number_f($TOTALES['TARJETA'])  }}</td>
    </tr>
    <tr>
      <td colspan="4">TOTAL CHEQUE:</td>
      <td class="text-end"> {{ Utilidades::number_f($TOTALES['CHEQUE'])  }}</td>
    </tr>
    <tr>
      <td colspan="4">TOTAL GIROS_TIGO:</td>
      <td class="text-end"> {{ Utilidades::number_f($TOTALES['TIGO_MONEY'])  }}</td>
    </tr>
    <tr>
      <td colspan="4" style="font-weight: 600;">TOTAL VENTAS DE LA SESIÓN:</td>
      <td class="text-end"> {{ Utilidades::number_f($TOTALES['TOTAL'])  }}</td>
    </tr>
  
   
  </tfoot>
</table>

<table id="tabla2" class="table fast-food-table bg-gradient   table-bordered">
  <thead>
    <tr>
      <th colspan="5">VENTAS ANULADAS EN ESTA SESIÓN</th>
    </tr>
    <tr>
      <th>Venta ID°</th>
      <th>Ticket</th>
      <th>Fecha</th>
      <th>Cliente</th>
      <th>Imp. Venta</th>
    </tr>
  </thead>
  <tbody>

    @foreach( $ANULADOS as $ticket)
    <tr>
      <td>{{$ticket->REGNRO}}</td>
      <td>{{$ticket->REGNRO}}</td>
      <td>{{$ticket->FECHA->format('d/m/Y')}}</td>
      <td>{{ is_null($ticket->cliente) ? '' :  $ticket->cliente->NOMBRE}}</td>
      <td class="text-end">{{ Utilidades::number_f( $ticket->TOTAL )}}</td>
    </tr>
    @endforeach


    @if( sizeof( $ANULADOS ) == 0 )
    <td colspan="5">No se registran ventas anuladas</td>
    @endif
  </tbody>

</table>



<table id="tabla3" class="table fast-food-table bg-gradient   table-bordered">
  <thead>
    <tr>
      <th colspan="4">SUMATORIA DE PRODUCTOS VENDIDOS</th>
    </tr>
    <tr>
      <th>Producto</th>
      <th>Ítem</th>
      <th class="text-center">Cantidad</th>
      <th>Imp. Venta</th>
    </tr>
  </thead>
  <tbody>

    @php
    $TOTAL_VENDIDOS=0;
    @endphp

    @foreach( $VENDIDOS as $detalle)


    <tr>
      <td>{{$detalle->ITEM}}</td>
      <td>{{$detalle->producto->DESCRIPCION}}</td>
      <td class="text-center">{{$detalle->CANTIDAD}}</td>
      <td class="text-end">{{ Utilidades::number_f($detalle->IMPORTE)  }}</td>
    </tr>
    @php
    $TOTAL_VENDIDOS+= $detalle->IMPORTE ;
    @endphp

    @endforeach

  </tbody>

  <tfoot>
    <tr>
      <td colspan="3">TOTAL DE VENTAS</td>
      <td class="text-end">
        {{ Utilidades::number_f($TOTAL_VENDIDOS)}}
      </td>
    </tr>

    @if( Illuminate\Support\Facades\Config::get("app.my_config.funciones.delivery") == "S" )
    <tr>
      <td colspan="3">Delivery:</td>
      <td class="text-end"> {{ Utilidades::number_f($TOTALES['TOTAL_DELIVERY'])  }}</td>
    </tr>
@endif

  </tfoot>

</table>
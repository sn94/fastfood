<?php

use App\Helpers\Utilidades;

?>

<style>
    table thead tr th {
        padding: 0px !important;
    }
</style>


@if( isset($print_mode))
@include("templates.print_report")
<h4 style="text-align: center; margin-bottom: 1px;"> {{ $titulo}} </h4>
<h6 style="text-align: center; margin-top: 1px;">Cajero: {{$CAJERO->REGNRO.' - '. $CAJERO->NOMBRES}}</h6>
@endif



<table class="table table-hover table-striped fast-food-table">
    <thead class="thead-dark">
        <tr>
            @if( !isset($print_mode))
            <th></th>
            <th></th>
            @if( Illuminate\Support\Facades\Config::get("app.my_config.funciones.delivery") == "S" )
            <th></th>
            @endif
            <th></th>
            <th></th>


            @endif
            <th>N° TICKET</th>
            <th>SESIÓN</th>
            <th>FECHA</th>
            <th>HORA</th>
            <th>CLIENTE</th>
            <th>TOTAL</th>
            <th>ESTADO</th>
        </tr>
    </thead>

    <tbody class="text-dark">

        @foreach( $VENTAS as $ven)


        @php
        $row_color= $ven->ESTADO =='P' ? 'table-danger' : ( $ven->ESTADO =='A' ? 'table-success' : 'table-secondary' );
        @endphp

        <tr class="{{$row_color}}">
            @if( !isset($print_mode))
            <td><a onclick="anular_confirmar(  event)" class="btn btn-danger btn-sm" href="{{url('ventas/anular/'.$ven->REGNRO)}}">Anular</a></td>
            <td><a onclick="imprimirTicket(<?= $ven->REGNRO ?>)" class="btn btn-danger btn-sm" href="#">Re-imprimir</a></td>

            @if( Illuminate\Support\Facades\Config::get("app.my_config.funciones.delivery") == "S" )
            @if( $ven->ESTADO =='P' )
            <td> <a onclick="anular_confirmar(  event)" href="{{url('ventas/confirmar/'.$ven->REGNRO)}}" class="btn btn-danger btn-sm"> <i class="fas fa-check-circle"></i>Confirmar</a> </td>
            @else
            <td>-</td>
            @endif
            @endif


            <td><a onclick="enviarTicketPorEmail(event)" class="text-dark" href="<?= url("ventas/ticket/" . $ven->REGNRO) ?>"><i class="fas fa-envelope"></i></a></td>
            <td> <a href="{{url('ventas/view/'.$ven->REGNRO)}}" class="text-dark"> <i class="fas fa-eye"></i></a> </td>

            @endif

            <td>{{$ven->REGNRO}}</td>
            <td>{{$ven->SESION}}</td>
            <td>{{ Utilidades::fecha_f($ven->FECHA) }}</td>
            <td>{{ date("H:i:s", strtotime(  $ven->created_at )   )}}</td>
            <td>{{ is_null($ven->cliente) ? '' : $ven->cliente->NOMBRE . "(". $ven->cliente->CEDULA_RUC . ")"}}</td>
            <td>{{ $ven->TOTAL}}</td>
            <td>{{ $ven->ESTADO =='A'  ? 'NORMAL'  : (  $ven->ESTADO =='P' ? 'PENDIENTE' : 'ANULADA')   }}</td>
        </tr>
        @endforeach


    </tbody>
</table>




<x-pretty-paginator :datos="$VENTAS" callback="fill_grill" />





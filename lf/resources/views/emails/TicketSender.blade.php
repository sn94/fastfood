@php

use App\Helpers\Utilidades;
use App\Models\Parametros;

$TICKETNRO= $VENTA->REGNRO;
$CLIENTECI= $VENTA->cliente->CEDULA_RUC;
$CLIENTENOM= $VENTA->cliente->NOMBRE;
$CAJERO= $VENTA->cajero->NOMBRES;

$TOTAL_A_PAGAR= 0;
$IMPORTE= $VENTA->IMPORTE;

//Mensaje Ticket
$params= Parametros::first();
$MENSAJE_TICKET= "";

 

@endphp

<img src="{{ $message->embed('./assets/images/logo.png') }}">

<h1 style="text-align: left;"> {{$params->MENSAJE_TICKET}}</h1>
<style>
    #Ticket {

        background-color: #ffff7d; 
        width: 100%;
    
    }

    table {
        margin: auto;
        background-color:  #ffff7d; 
        color: black;
    }



    p~p {
        margin-bottom: 0px;
        margin-top: 0px;
    }

    p,
    table tfoot tr td,
    table thead tr th,
    table tbody tr td {
        font-size: 0.58rem;
        padding: 0px;
        padding-left: 3px;
        padding-right: 3px;
        border-bottom: 1px solid black;
    }

    table tbody tr td {
        font-size: 0.5rem;

    }

    #TicketTitle {
        text-align: center;
        padding: 0px !important;
        font-weight: 600;
    }
</style>
@include("ventas.proceso.ticket.solo_datos")
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
$MENSAJE_TICKET= is_null( $params )? "" : $params->MENSAJE_TICKET;

@endphp

<style>
    @media print {
        #Ticket {
            width: 200px;
            margin: 0px;

        }

        #TicketTitle{
            text-align: center;font-weight: 600;
        }
        @page {
            size: auto;
            /* auto is the initial value */
            margin: 0mm;
            /* this affects the margin in the printer settings */
        }

        html {
            background-color: #FFFFFF;
            margin: 0px;
            font-family: Arial, Helvetica, sans-serif;
            /* this affects the margin on the html before sending to printer */
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
            margin: 0px;
        }

        table tbody tr td {
            font-size: 0.5rem;
        }
    }




    @media screen {
        #Ticket {
            width: 200px;
        }
        table{
            width: 200px;
        }

        * {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0px;
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
        }

        table tbody tr td {
            font-size: 0.5rem;
        }

        #TicketTitle{
            text-align: center;font-weight: 600;
        }
    }


</style>

@include("ventas.proceso.ticket.data")
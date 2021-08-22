@php

use App\Helpers\Utilidades;
use App\Models\Parametros;

$TICKETNRO= $VENTA->REGNRO;
$CON_DELIVERY= $VENTA->DELIVERY;
//Cliente
$CLIENTECI= $VENTA->cliente->CEDULA_RUC;
$CLIENTENOM= $VENTA->cliente->NOMBRE;
$CLIENTEDOM= $VENTA->cliente->DIRECCION;
$CLIENTETEL=$VENTA->cliente->TELEFONO;

$CAJERO= $VENTA->cajero->NOMBRES;

$TOTAL_A_PAGAR= 0;
$IMPORTE= $VENTA->IMPORTE;

//Mensaje Ticket
$params= Parametros::where("SUCURSAL", session("SUCURSAL"))->first();
$MENSAJE_TICKET= is_null( $params )? "" : $params->MENSAJE_TICKET;

@endphp

<style>
    @media print {
        #Ticket {
            position: absolute;
            left: 0;
            top: 0;
            width: 200px;
            margin: 0px;
            padding: 2px;
        }

        #TicketTitle {
            text-align: center;
            font-weight: 600;
        }


        @page {

            size: 200px;
            size: A4;
            margin: 0;
            padding: 0;
            
 
        }




        * {
            font-size: 9.5pt;
            padding: 0px;
            margin: 0px;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #FFFFFF;
        }

        p~p {
            margin-bottom: 0px;
            margin-top: 0px;
        }



        .footer {

            text-align: center !important;
            font-size: 9.4pt !important;
            position: relative !important;
            bottom: 0 !important;
        }
    }




    @media screen {
        #Ticket {
            width: 200px;
        }

        table {
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

        #TicketTitle {
            text-align: center;
            font-weight: 600;
        }

    }
</style>

@include("ventas.proceso.ticket.solo_datos")
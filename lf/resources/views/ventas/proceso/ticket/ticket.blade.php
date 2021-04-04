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
    }
</style>

<div id="Ticket">
    <p style="text-align: center;font-weight: 600;">TICKET N° {{$TICKETNRO}}</p>
    <p>CI° cliente: {{$CLIENTECI}} </p>
    <p>Razón social: {{$CLIENTENOM}} </p>
    <p>Cajero: {{$CAJERO}}</p>
    <p>Fecha:{{ date("d-m-Y")}} </p>
    <table>

        <thead>
            <tr>
                <th>Cód</th>
                <th>Artículo</th>
                <th>Cant.</th>
                <th>Precio</th>
                <th>Subt.</th>
            </tr>
        </thead>
        <tbody>


            @foreach( $DETALLE as $ITE)
            @php

            $precio= Utilidades::number_f( $ITE->P_UNITARIO);
            $subtotal= Utilidades::number_f( $ITE->P_UNITARIO * $ITE->CANTIDAD);
            $TOT_IVA10= 0;
            $TOT_IVA5= 0;

            @endphp
            <tr>
                <td>{{$ITE->REGNRO}}</td>
                <td>{{$ITE->producto->DESCR_CORTA}}</td>
                <td style="text-align: right;">{{$ITE->CANTIDAD}}</td>
                <td style="text-align: right;">{{$precio}}</td>
                <td style="text-align: right;">{{$subtotal}}</td>
            </tr>

            @php
            $TOTAL_A_PAGAR+= $ITE->P_UNITARIO * $ITE->CANTIDAD;
            $TOT_IVA10+= $ITE->TOT10;
            $TOT_IVA5+= $ITE->TOT5;

            @endphp



            @endforeach

            @php
            $VUELTO= $TOTAL_A_PAGAR - $IMPORTE;

            //NUMBER format
            $TOTAL_A_PAGAR= Utilidades::number_f( $TOTAL_A_PAGAR);
            $VUELTO= Utilidades::number_f( $VUELTO);
            @endphp


        <tfoot>
            <tr>
                <td colspan="2"> Total a pagar:</td>
                <td style="text-align: right;" colspan="2">{{$TOTAL_A_PAGAR}}</td>
            </tr>
            <tr>
                <td colspan="2">Importe:</td>
                <td style="text-align: right;" colspan="2">{{$IMPORTE== "" ? 0  :  $IMPORTE}} Gs.</td>
            </tr>
            <tr>
                <td colspan="2">Vuelto:</td>
                <td style="text-align: right;" colspan="2">{{$VUELTO}} Gs.</td>
            </tr>

            <tr>
                <td colspan="2">IVA 10%</td>
                <td colspan="2" style="text-align: right;">{{Utilidades::number_f($TOT_IVA10)}} Gs.</td>
            </tr>
            <tr>
                <td colspan="2">IVA 5%</td>
                <td colspan="2" style="text-align: right;">{{Utilidades::number_f($TOT_IVA5)}} Gs.</td>
            </tr>


        </tfoot>
        </tbody>
    </table>

    <p style="text-align: center;"> {{$MENSAJE_TICKET}}</p>
</div>
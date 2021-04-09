 <?php

use App\Helpers\Utilidades;

 ?>

 

<div id="Ticket">
  
    <table>

        <thead>
            <tr>
                <th colspan="5"> TICKET N° {{$TICKETNRO}}</th>
            </tr>
            <tr>
            <th>CI° cliente: </th> <th colspan="4">{{$CLIENTECI}}</th> 
            </tr>
            <tr>
            <th>Razón social: </th> <th colspan="4"> {{$CLIENTENOM}} </th> 
            </tr>
            <tr>
            <th>Cajero: </th> <th colspan="4"> {{$CAJERO}} </th> 
            </tr>
            <tr>   
            <th> Fecha: </th>  <th colspan="4"> {{ date("d-m-Y")}}</th>
            </tr>
            <tr>
                <th  style="text-align: center;" >Cód</th>
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
                <td style="text-align: center;">{{$ITE->REGNRO}}</td>
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
                <td colspan="4"> Total a pagar:</td>
                <td style="text-align: right;"  >{{$TOTAL_A_PAGAR}}</td>
            </tr>
            <tr>
                <td colspan="4">Importe:</td>
                <td style="text-align: right;"  >{{$IMPORTE== "" ? 0  :  $IMPORTE}} Gs.</td>
            </tr>
            <tr>
                <td colspan="4">Vuelto:</td>
                <td style="text-align: right;"  >{{$VUELTO}} Gs.</td>
            </tr>

            <tr>
                <td colspan="4">IVA 10%</td>
                <td  style="text-align: right;">{{Utilidades::number_f($TOT_IVA10)}} Gs.</td>
            </tr>
            <tr>
                <td colspan="4">IVA 5%</td>
                <td style="text-align: right;">{{Utilidades::number_f($TOT_IVA5)}} Gs.</td>
            </tr>


        </tfoot>
        </tbody>
    </table>

    <p style="text-align: center;"> {{$MENSAJE_TICKET}}</p>
</div>
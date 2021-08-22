 <?php

    use App\Helpers\Utilidades;
    use App\Models\Parametros;


    $parametros = Parametros::where("SUCURSAL", session("SUCURSAL"))->first();
    $RAZON_SOCIAL = is_null($parametros) ?  '' : $parametros->RAZON_SOCIAL;
    $DOMICILIO_COMERCIAL =  is_null($parametros) ?  '' : $parametros->DOMICILIO_COMERCIAL;
    $TELEFONO_COMERCIAL =  is_null($parametros) ?  '' : $parametros->TELEFONO_COMERCIAL;
    $RUC =  is_null($parametros) ?  '' : $parametros->RUC;
    $TITULO_TICKET =  is_null($parametros) ?  '' : $parametros->TITULO_TICKET;


    ?>



 <div id="Ticket">

     <table>




         <thead>
             @if( $RAZON_SOCIAL != "")
             <tr style="text-align: center;">
                 <th colspan="5"> {{$RAZON_SOCIAL}}</th>
             </tr>
             <tr style="text-align: center;">
                 <th colspan="5"> {{$RUC}}</th>
             </tr>
             <tr style="text-align: center;">
                 <th colspan="5"> {{$DOMICILIO_COMERCIAL}}</th>
             </tr>
             <tr style="text-align: center;">
                 <th colspan="5"> {{$TELEFONO_COMERCIAL}}</th>
             </tr>
             <tr style="text-align: center;">
                 <th colspan="5"> {{$TITULO_TICKET}}</th>
             </tr>

             @endif
             <tr style="text-align: center;">
                 <th colspan="5"> Ticket N° {{$TICKETNRO}}</th>
             </tr>
             <tr style="text-align: left;">
                 <th colspan="2">CI° cliente: </th>
                 <th colspan="3">{{$CLIENTECI}}</th>
             </tr>
             <tr style="text-align: left;">
                 <th colspan="2">Razón social: </th>
                 <th colspan="3"> {{$CLIENTENOM}} </th>
             </tr>
             @if( $CON_DELIVERY == "S")
             <tr style="text-align: left;">
                 <th>Domicilio: </th>
                 <th colspan="4"> {{$CLIENTEDOM}} </th>
             </tr>
             <tr style="text-align: left;">
                 <th>Teléf.: </th>
                 <th colspan="4"> {{$CLIENTETEL}} </th>
             </tr>
             @endif
             <tr style="text-align: left;">
                 <th>Cajero: </th>
                 <th colspan="4"> {{$CAJERO}} </th>
             </tr>
             <tr style="text-align: left;">
                 <th> Fecha: </th>
                 <th colspan="4"> {{ date("d-m-Y")}}</th>
             </tr>
             <tr>
                 <th style="text-align: center;">Cód</th>
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
                 <td style="text-align: center;">{{$ITE->producto->REGNRO }}</td>
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
             $IMPORTE= isset($VENTA) ? $VENTA->IMPORTE_PAGO : 0;


             //NUMBER format
             $CARGO_EXTRA= is_null($DELIVERY) ? 0 : ($DELIVERY->COSTO );
             $TOTAL_A_PAGAR_NUM= $TOTAL_A_PAGAR + $CARGO_EXTRA;
             $TOTAL_A_PAGAR= Utilidades::number_f( $TOTAL_A_PAGAR + $CARGO_EXTRA);

             $VUELTO= abs( $TOTAL_A_PAGAR_NUM - $IMPORTE );
             $VUELTO= Utilidades::number_f( $VUELTO);
             @endphp


         <tfoot>
             @if( ! is_null($DELIVERY))
             <tr>
                 <td colspan="4" class="p-0">{{$DELIVERY->DESCRIPCION}}</td>
                 <td class="text-end p-0"> {{ Utilidades::number_f( $DELIVERY->COSTO )}} </td>
             </tr>

             @endif

             <tr>
                 <td colspan="4"> Total a pagar:</td>
                 <td style="text-align: right;">{{$TOTAL_A_PAGAR}}</td>
             </tr>
             <tr>
                 <td colspan="4">Importe:</td>
                 <td style="text-align: right;">{{$IMPORTE }} </td>
             </tr>
             <tr>
                 <td colspan="4">Vuelto:</td>
                 <td style="text-align: right;">{{ $VUELTO}} </td>
             </tr>

             <tr>
                 <td colspan="4">IVA 10%</td>
                 <td style="text-align: right;">{{Utilidades::number_f( round($TOT_IVA10 / 11)   )   }} </td>
             </tr>




         </tfoot>
         </tbody>
     </table>

     <p style="text-align: center;"> {{$MENSAJE_TICKET}}</p>
     @isset($EJEMPLAR)
     <p class="footer">{{  $EJEMPLAR == "o" ? "ORIGINAL" : "COPIA" }}</p>
     @endisset

 </div>
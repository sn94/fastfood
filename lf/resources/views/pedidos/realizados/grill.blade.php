 <style>
     table thead tr th,
     table tbody tr td {
         padding: 0px !important;
     }
 </style>



 <table class="table table-hover table-striped bg-warning">
     <thead class="thead-dark">
         <tr>
         <th></th>
         <th>Estado</th>
            
             <th class="text-center">Pedido N°</th>
             <th>Fecha</th>
             <th>Descripción</th>
             <th>Pedido</th>
             <th>Aceptado</th>
             <th>Solicitado por</th>
             <th>Recibido por</th>
             <th>Observación</th>
            

         </tr>
     </thead>

     <tbody class="text-dark">

         @foreach( $PEDIDOS as $pedido)


         <tr>

         <td>

                 <!--Pendiente Aceptado Finalizado -->
                 @if( $pedido->ESTADO == "A")
                 <a onclick="mostrar_form(event)" href="{{url('pedidos/recibir/'.$pedido->NPEDIDO_ID)}}" class="btn btn-sm btn-success">Recibir</a>
                 @endif


             </td>

         <td>
                 @php
                 $colorEstado= ($pedido->ESTADO == "A" ? "text-success fw-bold" :
                 ($pedido->ESTADO == "P" ? "text-danger fw-bold" : ( $pedido->ESTADO == "R" ? "text-dark fw-bold" : "" ) ));
                 @endphp

                 @php
                 $textState= "Pendiente";
                 switch( $pedido->ESTADO){
                 case 'P': $textState= "Pendiente";break;
                 case 'A': $textState= "Aceptado";break;
                 case 'F': $textState= "Finalizado";break;
                 case 'R': $textState= "Rechazado (sin stock)";break;
                 }

                 @endphp
                 <span class="{{$colorEstado}}">
                     {{ $textState }}</span>
             </td>
             

             <td class="text-center">{{$pedido->REGNRO}}</td>
             <td>{{$pedido->FECHA->format('d/m/Y')}}</td>
             <td>{{$pedido->DESCRIPCION}}</td>
             <td>
                 {{$pedido->CANTIDAD}} {{ $pedido->MEDIDA}}

             </td>
             <td>
             @if( $pedido->ESTADO == "A" ||  $pedido->ESTADO == "F" )
             
             {{$pedido->CANT_ACEPTADA}} {{ $pedido->MEDIDA}}
             @endif 

             </td>

             <td>{{$pedido->SOLICITADO_POR == ''  ?  '' :  $pedido->SOLICITADO_POR}}</td>
             <td>{{ $pedido->RECIBIDO_POR}}</td>
             <td>{{$pedido->OBSERVACION=='' ? '':  $pedido->OBSERVACION}}</td>
            
         </tr>
         @endforeach


     </tbody>
 </table>


 <x-pretty-paginator :datos="$PEDIDOS" callback="fill_grill" />
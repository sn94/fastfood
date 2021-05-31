 <style>
     table thead tr th,
     table tbody tr td {
         padding: 0px !important;
     }
 </style>



 <table class="table table-hover table-striped fast-food-table">
     <thead class="thead-dark">
         <tr>
             <th></th>
             <th>Estado</th>
             <th class="text-center">Pedido N°</th>
             <th>Fecha</th>
             <th>Ítem</th>
             <th>Descripción</th>
             <th>Cantidad</th>
             <th>Solicitado por</th>
             <th>Recibido por</th>
             <th>Observación</th>



         </tr>
     </thead>

     <tbody class="text-dark">

         @foreach( $PEDIDOS as $pedido)


         <tr>

             <td>

                 @if( $pedido->ESTADO == "P")
                 <a onclick="mostrar_form(event)" href="{{url('pedidos/aprobar/'.$pedido->REGNRO)}}" class="btn btn-sm btn-success">APROBAR</a>
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
                 <span class="{{$colorEstado}}"> {{ $textState  }}</span>
             </td>

             <td class="text-center">{{$pedido->REGNRO}}</td>
             <td>{{$pedido->FECHA->format('d/m/Y')}}</td>
             <td>{{$pedido->ITEM}}</td>
             <td>{{$pedido->DESCRIPCION}}</td>
             <td>
                 {{$pedido->CANTIDAD}} {{ $pedido->MEDIDA}}

             </td>

             <td>{{$pedido->SOLICITADO_POR == ''  ?  '' :  $pedido->SOLICITADO_POR}}</td>
             <td>{{ $pedido->RECIBIDO_POR }}</td>
             <td>{{$pedido->OBSERVACION=='' ? '':  $pedido->OBSERVACION}}</td>

         </tr>
         @endforeach


     </tbody>
 </table>


 <x-pretty-paginator :datos="$PEDIDOS" callback="fill_grill" />
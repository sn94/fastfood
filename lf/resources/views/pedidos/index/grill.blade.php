 <style>
     table thead tr th,
     table tbody tr td {
         padding: 0px !important;
     }
 </style>


 <?php

    use App\Models\Stock;


    ?>

 <table class="table table-hover table-striped table-secondary">
     <thead class="thead-dark">
         <tr>
             <th>PEDIDO N°</th>
             <th>FECHA</th>
             <th>CANTIDAD</th>
             <th>SOLICITADO POR</th>
             <th>RECIBIDO POR</th>
             <th>OBSERVACIÓN</th>
             <th></th>

         </tr>
     </thead>

     <tbody class="text-dark">

         @foreach( $PEDIDOS as $pedido)

         <?php
            //Contextual row
            $classRow =     ($pedido->ESTADO == "A" ? "table-success" : ($pedido->ESTADO == "P" ?  "table-warning" :   "table-danger"));
            ?>

         <tr class="{{$classRow}}">

             <td>{{$pedido->NPEDIDO_ID}}</td>
             <td>{{$pedido->FECHA->format('d/m/Y')}}</td>
             <td>
                 {{$pedido->CANTIDAD}}

                 <?php

                    $stock =  Stock::find($pedido->ITEM);
                    $medida = ($stock->unidad_medida) ? $stock->unidad_medida->DESCRIPCION  :  "";
                    ?>
                 <span class="badge badge-warning">{{ $medida}}</span>

             </td>

             <td>{{$pedido->SOLICITADO_POR == ''  ?  '****' :  $pedido->SOLICITADO_POR}}</td>
             <td>{{ ($pedido->recibido_por) ? $pedido->recibido_por->NOMBRES : '****'}}</td>
             <td>{{$pedido->OBSERVACION=='' ? '****':  $pedido->OBSERVACION}}</td>
             <td>

                 @if( $pedido->ESTADO == "A")

                 <a onclick="mostrar_form(event)" href="{{url('pedidos/recibir/'.$pedido->NPEDIDO_ID)}}" class="btn btn-sm btn-warning">CONFIRMAR</a>
                 @endif

             </td>
         </tr>
         @endforeach


     </tbody>
 </table>

 {{ $PEDIDOS->links('vendor.pagination.default') }}
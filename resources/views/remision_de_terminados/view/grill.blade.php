 

 

<div class="container   mt-2 p-0  "> 
        <table class="table table-striped table-secondary text-dark">

            <thead>
                <tr  >
                    <th>Cód.</th>
                    <th>Descripción</th>
                    <th>Medida</th>
                    <th> Cantidad</th>
                    
                </tr>
            </thead>
            <tbody id="REMISION-DETALLE">

                @if( isset($DETALLE) )
                @foreach( $DETALLE as $ITEM)

                @if( $ITEM->TIPO == "PE")
                <tr id="{{$ITEM->ITEM}}" class="{{$ITEM->TIPO}}-class">

                    <td>{{$ITEM->stock->CODIGO}}</td>
                    <td>{{$ITEM->stock->DESCRIPCION}}</td>
                    <td>{{$ITEM->MEDIDA}}</td>
                    <td>{{$ITEM->CANTIDAD}}</td>
                 </tr>
                @endif
                @endforeach
                @endif
            </tbody>
            
        </table>
</div>


 
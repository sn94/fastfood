<div style="display:flex;flex-direction: row;">
    @php
    $tipos_item= [ "MP" => "MATERIA PRIMA", "PP"=> "PARA VENTA", "PE"=>"PRODUCTO ELABORADO" , "AF"=> "MOBILIARIO Y OTROS",
    "COMBO"=> "COMBO"];
    @endphp

    @if( $readonly == "S")

        @if( array_key_exists(  $value  , $tipos_item) )
        <span> {{$tipos_item[ $value]}}  </span>
        @else 
        <span>No registrado</span>
        @endif

    @else

    <select id="{{$id}}" name="{{$name}}" onchange="{{$callback}}" style="{{$style}}" class="{{$class}}" {{$atributos}}>
        @foreach( $tipos_item as $tkey=> $tval)
        @if( isset( $value) && $value == $tkey )
        <option selected value="{{$tkey}}"> {{$tval}}</option>
        @else
        <option value="{{$tkey}}"> {{$tval}}</option>
        @endif
        @endforeach
    </select>

    @endif


</div>
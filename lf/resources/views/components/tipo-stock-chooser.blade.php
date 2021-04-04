<div style="display:flex;flex-direction: row;"> 
    @php
    $tipos_item= [ "MP" => "MATERIA PRIMA", "PP"=> "PROD. VENTA", "PE"=>"PRODUCTO ELABORADO" , "AF"=> "MOBILIARIO Y OTROS"];
    @endphp
    <select id="{{$id}}" onchange="{{$callback}}" style="{{$style}}"  class="{{$class}}" >
        @foreach( $tipos_item as $tkey=> $tval)
        @if( isset( $value) && $value == $tkey )
        <option selected value="{{$tkey}}"> {{$tval}}</option>
        @else
        <option value="{{$tkey}}"> {{$tval}}</option>
        @endif
        @endforeach
    </select>

</div>
<select  id="{{$id}}" name="{{$name}}" class="{{$class}}" onchange="{{$callback}}">
    @php
    $FormasDePago= [ "TARJETA"=> "TARJETA DE CRÉD./DÉB.", "CHEQUE"=> "CHEQUE", "EFECTIVO"=> "EFECTIVO"];

    @endphp
    @foreach( $FormasDePago as $for=> $forval)
    @if( $value == $for)
    <option selected value="{{$for}}"> {{$forval}} </option>
    @else
    <option value="{{$for}}"> {{$forval}} </option>
    @endif
    @endforeach

</select>
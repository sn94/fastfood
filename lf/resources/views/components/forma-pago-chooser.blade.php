<select  id="{{$id}}" name="{{$name}}" class="{{$class}}" onchange="{{$callback}}">
    @php
    $FormasDePago= [ "EFECTIVO"=> "EFECTIVO", "TARJETA"=> "TARJETA DE CRÉD./DÉB.", "CHEQUE"=> "CHEQUE", "TIGO_MONEY"=> "TIGO MONEY"];

    @endphp
    @foreach( $FormasDePago as $for=> $forval)
    @if( $value == $for)
    <option selected value="{{$for}}"> {{$forval}} </option>
    @else
    <option value="{{$for}}"> {{$forval}} </option>
    @endif
    @endforeach

</select>
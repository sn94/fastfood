<?php

use App\Models\Familia;

$FAMILIAS = Familia::get();
?>
 
<div>
 
    <select id="{{$id}}"  onchange="{{$callback}}" style="{{$style}}" name="{{$name}}" class="{{$class}}" >
        @foreach($FAMILIAS as $FAMILY)
        @if( $value == $FAMILY->REGNRO)
        <option selected value="{{$FAMILY->REGNRO}}"> {{$FAMILY->DESCRIPCION }} </option>
        @else
        <option value="{{$FAMILY->REGNRO}}"> {{$FAMILY->DESCRIPCION }} </option>
        @endif
        @endforeach
    </select>
</div>
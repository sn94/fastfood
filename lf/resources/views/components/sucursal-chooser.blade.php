<?php

use App\Models\Sucursal;

$SUCS = Sucursal::get();
?>
  
 
    <select   class="{{$class}}" id="{{$id}}"  onchange="{{$callback}}" style="{{$style}}" name="{{$name}}">
        @foreach($SUCS as $suc)
        @if( $value == $suc->REGNRO)
        <option selected value="{{$suc->REGNRO}}"> {{$suc->DESCRIPCION }} </option>
        @else
        <option value="{{$suc->REGNRO}}"> {{$suc->DESCRIPCION }} </option>
        @endif
        @endforeach
    </select>
 
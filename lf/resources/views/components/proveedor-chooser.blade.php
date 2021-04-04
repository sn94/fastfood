<?php

use App\Models\Proveedores;

$PROVEEDORES=  Proveedores::get();
?>
<select onchange="{{$callback}}"  id="{{$id}}" name="{{$name}}" class="{{$class}}"  style="{{$style}}">
    
    @foreach( $PROVEEDORES as $proveedor)
    @if( $value == $proveedor->REGNRO)
    <option selected value="{{$proveedor->REGNRO}}"> {{ $proveedor->NOMBRE }} </option>
    @else
    <option value="{{$proveedor->REGNRO}}"> {{ $proveedor->NOMBRE }} </option>
    @endif
    @endforeach

</select>
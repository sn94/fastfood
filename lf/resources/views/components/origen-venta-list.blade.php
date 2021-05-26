 <!-- Very little is needed to make a happy life. - Marcus Antoninus -->



 <?php

     use App\Models\OrigenVenta;

   

    $ORIGENS = OrigenVenta::get();
    
    ?>



<select class="{{$class}}" id="{{$id}}" onchange="{{$callback}}" style="{{$style}}" name="{{$name}}">
     @foreach($ORIGENS as $ori)
     @if( $value == $ori->REGNRO)
     <option selected value="{{$ori->REGNRO}}"> {{$ori->DESCRIPCION }} </option>
     @else
     <option value="{{$ori->REGNRO}}"> {{$ori->DESCRIPCION }} </option>
     @endif
     @endforeach
 </select>
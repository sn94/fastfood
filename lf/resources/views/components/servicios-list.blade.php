 <!-- Very little is needed to make a happy life. - Marcus Antoninus -->



 <?php

     use App\Models\Servicios;
use Illuminate\Support\Facades\DB;

$ORIGENS = Servicios::select("servicios.*", DB::raw("FORMAT(servicios.COSTO, 0,'de_DE') AS COSTO") )
    ->orderBy("ORDEN","ASC")->get();
    
    ?>



<select class="{{$class}}" id="{{$id}}" onchange="{{$callback}}" style="{{$style}}" name="{{$name}}">
     @foreach($ORIGENS as $ori)
     @if( $value == $ori->REGNRO)
     <option selected value="{{$ori->REGNRO}}"> {{$ori->DESCRIPCION.' ('. $ori->COSTO . ' Gs.)  '}} </option>
     @else
     <option value="{{$ori->REGNRO}}"> {{$ori->DESCRIPCION.' ('. $ori->COSTO . ' Gs.)  '}} </option>
     @endif
     @endforeach
 </select>
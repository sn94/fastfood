@php

use App\Models\Usuario;
use App\Models\Ficha_produccion;

$NUEVO_NUMERO_ORDEN= Ficha_produccion::max("REGNRO") == "" ? ( 1 )  :  ( Ficha_produccion::max("REGNRO") + 1 );
$NUMERO_ORDEN= isset( $PRODUCCION) ? $PRODUCCION->REGNRO:  ( $NUEVO_NUMERO_ORDEN) ;
$FECHA= isset( $PRODUCCION) ? $PRODUCCION->FECHA: date('Y-m-d');
$CONCEPTO= isset( $PRODUCCION) ? $PRODUCCION->CONCEPTO: "";
//Quien Elaboro
$ELABORADO_POR= isset( $PRODUCCION) ? $PRODUCCION->ELABORADO_POR: "";

@endphp




<div class="row  mt-2 pt-1 pb-2 pr-2 pl-2 pr-md-2 pl-md-2 g-1">

    <div class="col-12 col-sm-4 col-md-2 col-lg-2   mb-1 f-flex  flex-column"   >
 
            <label   class="mt-1 text-light" for="element_7">ORDEN N°: </label>
            <input  readonly  value="{{$NUMERO_ORDEN}}"  class="form-control mt-1" type="text" />
    
    </div>


    <div class="col-12 col-sm-8 col-md-4 col-lg-4   mb-1">
        
            <label   class="mt-1 text-light" for="element_7">ELABORADO POR: </label>
            <input maxlength="100" name="ELABORADO_POR" value="{{$ELABORADO_POR}}"   class="form-control mt-1" type="text" />
        
    </div>
    <div class="col-12 col-sm-3 col-md-2 col-lg-2  mb-1 pl-0 d-flex flex-column">

        <label   class="mt-1 text-light" for="element_7">FECHA: </label>
        <input value="{{$FECHA}}" name="FECHA"   class="form-control mt-1" type="date" />

    </div>

    <div class="col-12 col-sm-9 col-md-4 col-lg-4  mb-1 d-flex flex-column"> 
            <label  class="mt-1 text-light" for="element_7">OBSERVACIÓN: </label>
            <input value="{{$CONCEPTO}}" name="CONCEPTO"   class="form-control mt-1" type="text" />
    
    </div>
    <!--     end     CABECERA  --->
</div>
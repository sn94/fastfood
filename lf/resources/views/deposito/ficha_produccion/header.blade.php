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




<div class="row  bg-dark mt-2 pt-1 pb-2 pr-2 pl-2 pr-md-2 pl-md-2">

    <div class="col-12 col-md-2   mb-1"  style="display: grid;  grid-template-columns: 45%  55%;">
 
            <label style=" color:white;" class="mt-1" for="element_7">ORDEN N°: </label>
            <input  readonly  value="{{$NUMERO_ORDEN}}"  class="form-control mt-1" type="text" />
    
    </div>


    <div class="col-12 col-md-4   mb-1">
        <div style="display: grid;  grid-template-columns: 40%  60%;">
            <label style="grid-column-start: 1;color:white;" class="mt-1" for="element_7">ELABORADO POR: </label>
            <input maxlength="100" name="ELABORADO_POR" value="{{$ELABORADO_POR}}" style="grid-column-start: 2;" class="form-control mt-1" type="text" />
        </div>
    </div>
    <div class="col-12 col-md-2  mb-1 pl-0" style="display: grid;  grid-template-columns: 25%  75%;">

        <label style="grid-column-start: 1;color:white;" class="mt-1" for="element_7">FECHA: </label>
        <input value="{{$FECHA}}" name="FECHA" style="grid-column-start: 2;" class="form-control mt-1" type="date" />

    </div>

    <div class="col-12 col-md-6  mb-1">
        <div style="display: grid;  grid-template-columns: 20%  80%;">
            <label style="grid-column-start: 1;color:white;" class="mt-1" for="element_7">OBSERVACIÓN: </label>
            <input value="{{$CONCEPTO}}" name="CONCEPTO" style="grid-column-start: 2;" class="form-control mt-1" type="text" />
        </div>
    </div>
    <!--     end     CABECERA  --->
</div>
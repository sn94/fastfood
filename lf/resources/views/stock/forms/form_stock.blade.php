<?php

use App\Models\Medidas;

$MEDIDAS =  Medidas::get();

?>
<div class="row">
    <div class="col-12 col-md-6">

        <label style="grid-column-start: 1;" class="mt-1" for="element_7">FAMILIA: </label>
        <select class="form-control" name="FAMILIA">
            @foreach($FAMILIAS as $FAMILY)
            @if( $FAMILIA == $FAMILY->REGNRO)
            <option selected value="{{$FAMILY->REGNRO}}"> {{$FAMILY->DESCRIPCION }} </option>
            @else
            <option value="{{$FAMILY->REGNRO}}"> {{$FAMILY->DESCRIPCION }} </option>
            @endif
            @endforeach
        </select>
        
        <label>PRECIO DE VENTA: </label>
        <input name="PVENTA" class="form-control text-end entero" type="text" maxlength="10" value="{{$PVENTA}}" />

        <!-- MITAD -->

        <label>VENDIDO POR MITAD: </label>
        <input name="PVENTA_MITAD" class="form-control text-end entero" type="text" maxlength="10" value="{{$PVENTA_MITAD}}" />
        <label>PRECIO EXTRA: </label>
        <input name="PVENTA_EXTRA" class="form-control text-end entero" type="text" maxlength="10" value="{{$PVENTA_EXTRA}}" />



    </div>

    <div class="col-12 col-md-6">
        <label>PRECIO DE COSTO: </label>
        <input name="PCOSTO" class="form-control text-end entero" type="text" maxlength="80" value="{{$PCOSTO}}" />


        <label>UNIDAD DE MEDIDA: </label>
        <select name="MEDIDA" class="form-control">


            @foreach( $MEDIDAS as $val)
            @if( $val == $MEDIDA)
            <option selected value="{{$val->REGNRO}}"> {{$val->DESCRIPCION}} </option>
            @else
            <option value="{{$val->REGNRO}}"> {{$val->DESCRIPCION}} </option>
            @endif
            @endforeach
        </select>

        <div class="row">
            <div class="col-12 col-sm-6">
                <label for="element_5">STOCK MÍN.: </label>
                <input name="STOCK_MIN" class="form-control decimal " type="text" maxlength="20" value="{{$STOCK_MIN}}" />

            </div>
            <div class="col-12 col-sm-6">
                <label for="element_6">STOCK MÁX.: </label>
                <input name="STOCK_MAX" class="form-control decimal " type="text" maxlength="20" value="{{$STOCK_MAX}}" />
            </div>
        </div>






    </div>
</div>
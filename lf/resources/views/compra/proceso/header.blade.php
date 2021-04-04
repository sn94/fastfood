<?php

use App\Models\Compras;

$IDCOMPRA = Compras::max("REGNRO") + 1;
$REGNRO = isset($COMPRA) ?  $COMPRA->REGNRO :  $IDCOMPRA;
$NUMERO = isset($COMPRA) ?  $COMPRA->NUMERO :  "";
$CONDICION_CON = isset($COMPRA) ? ($COMPRA->CONDICION == "CONTADO" ? "si" : "no") :  "si";
$CONDICION_CRE = isset($COMPRA) ? ($COMPRA->CONDICION == "CREDITO" ? "si" : "no") :  "no";
$PROVEEDOR = isset($COMPRA) ? ($COMPRA->PROVEEDOR) :  "";
$PROVEEDOR_NAME = isset($COMPRA) ? (is_null($COMPRA->proveedor) ? "" : $COMPRA->proveedor->NOMBRES) :  "";
$FECHA = isset($COMPRA) ? ($COMPRA->FECHA) :  date('Y-m-d');
$CONCEPTO = isset($COMPRA) ? ($COMPRA->CONCEPTO) :  "";
$FORMA_PAGO = isset($COMPRA) ? ($COMPRA->FORMA_PAGO) :  "";
?>

<div class="col-12 col-sm-4 col-md-2   col-lg-2  ">

    <label for="element_7">N° COMPRA:</label>
    <input name="REGNRO" readonly value="{{$REGNRO}}" class="form-control form-control-sm" type="text" />

</div>

<div class="col-12  col-sm-4 col-md-2   col-lg-2  g-1">

    <label for="element_7">FACTURA N°: </label>
    <input value="{{$NUMERO}}" name="NUMERO" class="form-control form-control-sm" type="text" />

</div>

<div class="col-12  col-sm-4  col-md-2  col-lg-2 g-1" style="display: flex;flex-direction:  column;">

    <x-pretty-radio-button name="CONDICION" value="CONTADO" label="CONTADO" :checked="$CONDICION_CON" size="16" />
    <x-pretty-radio-button name="CONDICION" value="CREDITO" label="CREDITO" :checked="$CONDICION_CRE" size="16" />

</div>

<div class="col-12   col-sm-3  col-md-2 col-lg-2 g-1">

    <label for="element_7">FECHA: </label>
    <input required value="{{$FECHA}}" name="FECHA" class="form-control form-control-sm " type="date" />

</div>



<div class="col-12  col-sm-3 col-md-4 col-lg-4 ">
    <label for="element_7">FORMA DE PAGO: </label>

    <select class="form-control form-control-sm" name="FORMA_PAGO">
        @php
        $FormasDePago= [ "TARJETA"=> "TARJETA DE CRÉD./DÉB.", "CHEQUE"=> "CHEQUE", "EFECTIVO"=> "EFECTIVO"];

        @endphp
        @foreach( $FormasDePago as $for=> $forval)
        @if( $FORMA_PAGO == $for)
        <option selected value="{{$for}}"> {{$forval}} </option>
        @else
        <option value="{{$for}}"> {{$forval}} </option>
        @endif
        @endforeach

    </select>

</div>


<div class="col-12  col-sm-6  col-md-5 col-lg-5 "  >


    <div style="display: flex; flex-direction: row;">
        <label >PROVEEDOR:</label>
        <a href="#" onclick="crear_proveedor()"><i class="fa fa-plus"></i></a>
        <a href="#" onclick="buscador_de_personas({KEY:'#PROVEEDOR-KEY',NAME:'#PROVEEDOR-NAME'})"><i class="fa fa-search"></i></a>
    </div>
  <div style="display: flex; flex-direction: row;">
  <input value="{{$PROVEEDOR}}" disabled id="PROVEEDOR-KEY" style="width: 100px; flex-shrink: 2;" class="form-control form-control-sm" type="text" name="PROVEEDOR">
    <input value="{{$PROVEEDOR_NAME}}" disabled id="PROVEEDOR-NAME" class="form-control form-control-sm " type="text" />

  </div>

</div>




<div class="col-12  col-md-7 col-lg-7"> 
        <label  >OBSERVACIÓN: </label>
        <input value="{{$CONCEPTO}}" name="CONCEPTO"   class="form-control form-control-sm form-compra " type="text" />
 
</div>
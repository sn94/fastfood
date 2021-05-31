<!--Hacer un pedido -->
<?php

use Psy\CodeCleaner\IssetPass;

$PEDIDO_ID=  isset($PEDIDO )?  $PEDIDO->REGNRO  :  NULL; 
$FECHA_VENTA=  isset($PEDIDO )?  ( is_null($PEDIDO->FECHA_VENTA) ? '' :$PEDIDO->FECHA_VENTA->FORMAT('Y-m-d'))  :  date("Y-m-d"); 
$STOCK_ID=  isset($STOCK) ?     $STOCK->REGNRO : (  isset($DETALLE)?  $DETALLE[0]->ITEM  :   ''  );
$SOLICITADO_POR= isset($PEDIDO )?  $PEDIDO->SOLICITADO_POR  :  $SOLICITADO_POR ; 
$CANTIDAD= isset( $DETALLE ) ? $DETALLE[0]->CANTIDAD  :  $CANTIDAD ;
$MEDIDA= isset($STOCK) ?  $STOCK->unidad_medida->DESCRIPCION  : "" ;


//action

$formAction=  isset(  $PEDIDO) ?  url("pedidos/editar")  : url("pedidos/create");
?>


<form onsubmit="pedir_recibir(event)" method="POST" action="{{$formAction}}">
    @csrf

    @if( isset($PEDIDO_ID))
<input type="hidden" name="REGNRO"  value="{{$PEDIDO_ID}}">
    @endif 

    <div class="container col-12 col-md-10 col-lg-8">
        <div class="row">
            <div class="col-12 ">
                <label style="font-size: 15px;">Correspondiente a ventas en fecha:</label>
            </div>
            <div class="col-12">
                <input class="w-100" value="{{$FECHA_VENTA}}"  type="date" name="FECHA_VENTA" id="FECHA_VENTA" >
            </div>


            <div class="col-12 ">
                <label style="font-size: 15px;">Solicitado por:</label>
            </div>
            <div class="col-12">
                <input class="w-100" value="{{$SOLICITADO_POR}}" type="text" name="SOLICITADO_POR" maxlength="30">
            </div>
            <div class="col-12 ">
                <label style="font-size: 15px;">Cantidad:</label>
            </div>
            <div class="col-12">
                <input onfocus="if(this.value=='0') this.value='';" onblur="if(this.value=='') this.value='0';" style="width: 100px;" value="{{$CANTIDAD}}" required type="text" name="CANTIDAD" class="decimal" oninput="formatoNumerico.formatearDecimal(event)">
               

                <span class="badge bg-success" style="font-size: 18px;"> {{$MEDIDA}}</span>
            </div>
            <div class=" col-sm-12 ">
                <button class="mt-2 btn fast-food-form-button w-100" type="submit">Guardar</button>
            </div>
        </div>
        <input type="hidden" name="ITEM" value="{{  $STOCK_ID}}">
    </div>

</form>
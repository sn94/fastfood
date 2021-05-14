<?php 
$MODULO_FLAG =    isset($_GET['m']) ? $_GET['m'] :  "";
$QUERY_FLAG =  $MODULO_FLAG == "c" ? "?m=c"  :  ""; 
?>

<div id="loaderplace"></div>

@if( request()->ajax())
<input type="hidden" id="REDIRECCIONAR" value="NO">
@else
<input type="hidden" id="REDIRECCIONAR" value="SI">
@endif



<form class=" text-light    bg-dark  p-1 rounded" id="STOCKFORM" action="{{url('stock')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardarStock(event)" enctype="multipart/form-data">


    @if( isset($stock) )
    <input type="hidden" name="REGNRO" value="{{$stock->REGNRO}}">

    @method("PUT")
    @else
    <input type="hidden" name="_method" value="POST">
    @endif


    <div class="row">


        <!--Botones -->
        <div class="col-12 col-md-4 ">
            <div class="row mb-0   pt-1 pb-0  pr-1 pl-1">
                <div class="col-12  col-md-4 pb-1">
                    <div class="btn-group">
                        @if( ! request()->ajax())
                        <a class="btn btn-sm  btn-secondary mr-2" href="<?= url("stock$QUERY_FLAG") ?>"> VOLVER </a>
                        <a class="btn btn-secondary btn-sm" href="{{url('stock/create'.$QUERY_FLAG)}}">NUEVO</a>
                        @endif
                        <button type="submit" class="btn btn-warning btn-sm">GUARDAR</button>

                    </div>
                </div>
                <div class="col-12 offset-md-7 col-md-1 pb-1"></div>
            </div>
        </div>
        <!--End Botones -->

        <div class="col-12 col-md-4">
            <h3 class="text-center ">Ficha de Stock</h3>
        </div>
    </div>



    @include("stock.forms.index")


</form>



 
<?php 

$MODULO_FLAG=    isset($_GET['m']) ? $_GET['m'] :  "";
$QUERY_FLAG=  $MODULO_FLAG!= "c"  ? ""  :    "?m=$MODULO_FLAG";

//Plantilla
$templateName = "";

if ( $MODULO_FLAG !=  "c") :
    $templateName = "templates.admin.index";
elseif ( $MODULO_FLAG == "c") :
    $templateName = "templates.caja.index";
endif;

?> 



@extends( $templateName)

@section("content")



<input type="hidden" id="CLIENTES-INDEX" value="{{url('clientes'.$QUERY_FLAG)}}">



<a class="btn btn-sm btn-warning" href="<?= url("clientes".$QUERY_FLAG) ?>"> IR ATRÁS</a>

<h2 class="text-center mt-2"  >Ficha de cliente</h2>


<div id="loaderplace"></div>
<form  id="FORM-CLIENTE" action="{{url('clientes'.$QUERY_FLAG)}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)">



        <input type="hidden" id="REDIRECCIONAR" value="SI">
        <input type="hidden" name="_method" value="PUT">
        @include("clientes.form")


</form>


@endsection
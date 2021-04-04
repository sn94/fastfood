<?php

$MODULO_FLAG=    isset($_GET['m']) ? $_GET['m'] :  "" ;
$QUERY_FLAG=  $MODULO_FLAG == "c" ? "?m=c"  :  "";
//Plantilla
$templateName = "";
$create = "";
$index = "";

if (  $MODULO_FLAG !=  "c") :
  $templateName = "templates.admin.index";
 

elseif ( $MODULO_FLAG  ==  "c") :
  $templateName = "templates.caja.index";
 
endif;

?>
@extends( $templateName)


@section("content")

<div class="container col-12 col-md-12 pb-1 bg-dark">
@include("stock.create_ajax")
</div>
@endsection
 
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





@include("clientes.create_ajax")




@endsection
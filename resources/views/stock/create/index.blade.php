<?php

$MODULO_FLAG =    isset($_GET['m']) ? $_GET['m'] :  "";
$QUERY_FLAG =  $MODULO_FLAG == "c" ? "?m=c"  :  "";
//Plantilla
$templateName = "";
$create = "";
$index = "";

 
if ($MODULO_FLAG !=  "c") :
  $templateName = "templates.admin.index";


elseif ($MODULO_FLAG  ==  "c") :
  $templateName = "templates.caja.index";

endif;

?> 

@if( (request()->ajax()))
  
  @if( isset($stock) )
   <?=view("stock.create.ajax",  ['stock'=> $stock ,  'RECETA'=> $RECETA, 'COMBO'=>$COMBO])?>
   @else 
   <?=view("stock.create.ajax")?>
   @endif
@else
@if( isset($stock) )
  <?=view("stock.create.no_ajax",   ['stock'=> $stock ,  'RECETA'=> $RECETA, 'COMBO'=>$COMBO ] )?>
  @else 
  <?=view("stock.create.no_ajax" )?>
  @endif
@endif
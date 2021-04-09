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
  
   <?=view("stock.create.ajax")?>
@else
  <?=view("stock.create.no_ajax")?>
@endif
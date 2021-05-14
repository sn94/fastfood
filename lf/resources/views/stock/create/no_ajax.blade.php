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

@if( ! request()->ajax())
  @extends( $templateName)
  @section("content")
  <div class="container col-12 col-md-12 pb-1 mt-1">
    @include("stock.create.ajax")
  </div>
@endsection

@else
  @include("stock.create.ajax")
@endif
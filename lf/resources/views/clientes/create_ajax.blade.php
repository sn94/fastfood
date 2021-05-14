<?php

$MODULO_FLAG =    isset($_GET['m']) ? $_GET['m'] :  "";
$QUERY_FLAG =  $MODULO_FLAG != "c"  ? ""  :    "?m=$MODULO_FLAG";


?>


<input type="hidden" id="CLIENTES-INDEX" value="{{url('clientes'.$QUERY_FLAG)}}">


<div class="container-fluid bg-dark text-light col-12 col-md-6 mt-1">

        @if( ! request()->ajax() )
        <a class="btn btn-sm btn-warning" href="<?= url("clientes" . $QUERY_FLAG) ?>"> IR ATRÁS</a>
        @endif

        <h2 class="text-center mt-2">Ficha de cliente</h2>

        <form id="FORM-CLIENTE" action="{{url('clientes'.$QUERY_FLAG)}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)">


                <div id="loaderplace"></div>



                @if(isset($cliente))
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="REDIRECCIONAR" value="SI">
                @else

                        @if( request()->ajax() )
                        <input type="hidden" id="REDIRECCIONAR" value="NO">
                        @else
                        <input type="hidden" id="REDIRECCIONAR" value="SI">
                        @endif

                        <input type="hidden" name="_method" value="POST">
                @endif


                @include("clientes.form")


        </form>
</div>
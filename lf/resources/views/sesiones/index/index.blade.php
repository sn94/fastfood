<?php

$MODULO_FLAG=    isset($_GET['m']) ? $_GET['m'] :  "" ;
$QUERY_FLAG=  $MODULO_FLAG == "c" ? "?m=c"  :  "";
//Plantilla
$templateName = "";
 

if (  $MODULO_FLAG !=  "c") :
  $templateName = $PLANTILLA;
 

elseif ( $MODULO_FLAG  ==  "c") :
  $templateName = "templates.caja.index";
endif;
 
?>



@extends( $templateName) 



@section("PageTitle")
Sesiones
@endsection
@section("content")





@php 
$SESION_INDEX_URL= $INDIVIDUAL ? url('sesiones/list'.$QUERY_FLAG)  :  url('sesiones').$QUERY_FLAG;
@endphp

<input type="hidden" id="SESION-INDEX-URL" value="{{$SESION_INDEX_URL}}">


<div class="container col-12 col-md-12 col-lg-10 bg-dark text-light pb-2">
    <h3 class="text-center mt-2"  >Sesiones</h3>



    <div id="loaderplace"></div>



    <x-search-report-downloader placeholder="BUSCAR POR DESCRIPCION" callback="buscarSesiones()" showSearcherInput="N">

        @if( ! $INDIVIDUAL)
        <label style="display: flex;flex-direction: row;color:white;"> Por Sucursal:
            <x-sucursal-chooser id="SUCURSAL" value="" name="" style="" callback="buscarSesiones()"></x-sucursal-chooser>
        </label>
        @else
        <input type="hidden" id="USER-ID" value="{{session('ID')}}">
        @endif
        <label style="display: flex;flex-direction: row;color:white;"> Por estado:
            <select id="ESTADO" onchange="buscarSesiones()">
                <option value="A">ABIERTAS</option>
                <option value="C">CERRADAS</option>
            </select>
        </label>


    </x-search-report-downloader>


    <div id="grill" style="min-height: 300px;">

        @include("sesiones.index.grill")
    </div>
</div>


<script>
    function show_loader() {
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
    }

    function hide_loader() {
        $("#loaderplace").html("");
    }


    function buscarSesiones() {
        inicializar();
        dataSearcher.formatoHtml();
    }

    function descargarArqueo(ev) {
        ev.preventDefault();
        dataSearcher.setMetodo = "GET";
        dataSearcher.setUrl = ev.target.href;
       /* //params
        let sucursal = $("#SUCURSAL").val();
        let estado = $("#ESTADO").val();
        let usuario= $("#USER-ID").val();
        */
        dataSearcher.formatoPdf();
    }

    function inicializar() {
        dataSearcher.setMetodo = "post";
        dataSearcher.setRequestContentType = "application/json";
        dataSearcher.setDataLink =  "#SESION-INDEX-URL";
        dataSearcher.setOutputTarget = "#grill";
        //params
        let sucursal = $("#SUCURSAL").val();
        let estado = $("#ESTADO").val();
        let usuario= $("#USER-ID").val();

        let paramsr=  { ESTADO:  estado};
        if( sucursal != undefined)  paramsr.SUCURSAL= sucursal;
        if( usuario != undefined)  paramsr.USUARIO= usuario;
        
        dataSearcher.setParametros =   paramsr;
    }
</script>
@endsection


@section("jsScripts")


<script src="<?= url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= url("assets/xls_gen/xls_ini.js?v=" . rand(0.0, 100)) ?>"></script>
<script src="<?= url("assets/js/buscador.js?v=" . rand(0.0, 100)) ?>"></script>



<script>
    var dataSearcher = undefined;
    window.onload = function() {
        dataSearcher = new DataSearcher();
        inicializar();
    }
</script>
@endsection
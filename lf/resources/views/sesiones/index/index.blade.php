<?php

$MODULO_FLAG =    isset($_GET['m']) ? $_GET['m'] :  "";
$QUERY_FLAG =  $MODULO_FLAG == "c" ? "?m=c"  :  "";
//Plantilla
$templateName = "";


if ($MODULO_FLAG !=  "c") :
    $templateName = "templates.admin.index";
elseif ($MODULO_FLAG  ==  "c") :
    $templateName = "templates.caja.index";
endif;

$SESION_INDEX_URL=  url('sesiones').$QUERY_FLAG;
 

?>



@extends( $templateName)



@section("PageTitle")
Sesiones
@endsection
@section("content")




<!--Modal -->
<x-fast-food-modal id="sesiones-modal" title="Informe de arqueo" size="modal-lg"></x-fast-food-modal>


<input type="hidden" id="SESION-INDEX-URL" value="{{$SESION_INDEX_URL}}">
 







<div class="container col-12 col-md-12 col-lg-10 bg-dark text-light pb-2">
    <h3 class="text-center mt-2">Sesiones</h3>



    <div id="loaderplace"></div>



    <x-search-report-downloader placeholder="BUSCAR POR DESCRIPCION" callback="buscarSesiones()" showSearcherInput="N">
 
            @if( $MODULO_FLAG !=  "c")
            <label  class="text-light d-flex flex-row"> Por Sucursal: 
                <x-sucursal-chooser id="SUCURSAL" value="" name="" style="" callback="buscarSesiones()"></x-sucursal-chooser></label>
            @else
            @if( !is_null($USUARIO) )
            <input type="hidden" id="USER-ID" value="{{$USUARIO}}">
            @endif
            @endif
            <label class="text-light d-flex flex-row"> Por estado:

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
    function buscarSesiones() {
        inicializar();
        dataSearcher.formatoHtml();
    }

    //descarga en pdf
    async function descargarArqueoPdf(ev) {
        ev.preventDefault();
        let enlace_descarga = ev.target.href;
        dataSearcher.setMetodo = "GET";
        dataSearcher.setUrl = enlace_descarga;
        await dataSearcher.formatoPdf();
    }

    //Solo el Html
    async function descargarArqueo(ev) {
        ev.preventDefault();
        let enlace_descarga = ev.target.href;
        dataSearcher.setMetodo = "GET";
        dataSearcher.setUrl = enlace_descarga;
        dataSearcher.outputTarget = "#sesiones-modal .modal-body";
        //espere
        $("#sesiones-modal .modal-body").html("Espere..");
        $("#sesiones-modal").modal("show");
        await dataSearcher.formatoHtml();

        //Incorporar boton de descarga pdf

        $("#sesiones-modal .modal-body").prepend(` <a onclick="descargarArqueoPdf(event)" href="${enlace_descarga}" class="btn btn-sm btn-danger">
        Descargar PDF <i class='fa fa-download' ></i> </a>`);
    }

    function inicializar() {
        dataSearcher.setMetodo = "post";
        dataSearcher.setRequestContentType = "application/json";
        dataSearcher.setDataLink = "#SESION-INDEX-URL";
        dataSearcher.setOutputTarget = "#grill";
        //params
        let sucursal = $("#SUCURSAL").val();
        let estado = $("#ESTADO").val();
        let usuario = $("#USER-ID").val();

        let paramsr = {
            ESTADO: estado
        };
        if (sucursal != undefined) paramsr.SUCURSAL = sucursal;
        if (usuario != undefined) paramsr.USUARIO = usuario;

        dataSearcher.setParametros = paramsr;
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
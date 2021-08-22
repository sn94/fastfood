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

$SESION_INDEX_URL =  url('sesiones') . $QUERY_FLAG;


?>



@extends( $templateName)



@section("PageTitle")
Sesiones
@endsection
@section("content")


@include("buscador.Buscador")

<!--Modal -->
<x-fast-food-modal id="sesiones-modal" title="Informe de arqueo" size="modal-lg"></x-fast-food-modal>



<input type="hidden" id="SESION-INDEX-URL" value="{{$SESION_INDEX_URL}}">

<div class="container-fluid mt-1 col-12 col-md-12 col-lg-11 col-xl-10 fast-food-bg   pb-5">
    <h3 class="fast-food-big-title" >Sesiones</h3>
    <div id="loaderplace"></div>

    <x-search-report-downloader placeholder="BUSCAR POR DESCRIPCION" callback="buscarSesiones()" showSearcherInput="N">

        @if( $MODULO_FLAG != "c")
        <label class="  d-flex flex-column pe-1"> Por Sucursal:
            <x-sucursal-chooser id="SUCURSAL" value="" name="" style="" callback="buscarSesiones()"></x-sucursal-chooser>
        </label>
        @else
        @if( !is_null($USUARIO) )
        <input type="hidden" id="USER-ID" value="{{$USUARIO}}">
        @endif
        @endif

        <label class="  d-flex flex-column pe-1"> Por estado:

            <select id="ESTADO" onchange="buscarSesiones()">
                <option value="A">ABIERTAS</option>
                <option value="C">CERRADAS</option>
            </select>

        </label>


        <div class="d-flex flex-column pe-1 ">
            <label class="pe-1"> Desde: </label>
            <input class="form-control form-control-sm" type="date" id="FECHA_DESDE" onchange="buscarSesiones()">
        </div>

        <div class="d-flex flex-column">
            <label class="pe-1"> Hasta:</label>
            <input class="form-control form-control-sm" type="date" id="FECHA_HASTA" onchange="buscarSesiones()">
        </div>

        @if( $MODULO_FLAG != "c")
        <div class="d-flex flex-column">
            <label> Cajero/a:</label>
            <div class="d-flex flex-row ">
                <a href="#" onclick="abrirBuscadorCajero()"><i class="fa fa-search"></i></a>
                <input type="hidden" id="CAJERO">
                <input type="text" readonly id="CAJERO-NOMBRE" class="form-control-sm">
                <x-pretty-checkbox callback="buscarSesiones()" id="F_CAJERO" name="" value="N" onValue="S" offValue="N" label=""></x-pretty-checkbox>
            </div>


        </div>
        @endif

    </x-search-report-downloader>


    <div id="grill" style="min-height: 300px;">

        @include("sesiones.index.grill")
    </div>
</div>


<script>
    function abrirBuscadorCajero() {
        //KEY:'#PROVEEDOR-KEY',NAME:'#PROVEEDOR-NAME


        Buscador.url = "<?= url("usuario") ?>";
        Buscador.htmlFormForParams = "<input type='hidden' value='CAJA' /> ";
        Buscador.columnNames = ["REGNRO", "CEDULA", "NOMBRES"];
        Buscador.columnLabels = ['ID', 'CÉDULA', 'NOMBRES'];
        Buscador.callback = function(seleccionado) {

            $('#CAJERO').val(seleccionado.REGNRO);
            $('#CAJERO-NOMBRE').val(seleccionado.NOMBRES);
            buscarSesiones();
        };
        Buscador.render();
    }


    function buscarSesiones(ev_url_opcional) {
        if (ev_url_opcional) ev_url_opcional.preventDefault();

        let url_opcional = ev_url_opcional ? ev_url_opcional.target.href : undefined;
        inicializar(url_opcional);
        dataSearcher.formatoHtml();
    }

    //enviar arqueo por email
    async function enviarArqueoPorEmail(ev) {
        ev.preventDefault();
        let enlace_descarga = ev.currentTarget.href;
        show_loader();
        let req = await fetch(enlace_descarga, {
            headers: {
                formato: "email"
            }
        });
        let resp = await req.json();
        hide_loader();
        if ("ok" in resp) alert(resp.ok);
        else alert(resp.err);
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

    function inicializar(url_opcional) {
        dataSearcher.setMetodo = "post";
        dataSearcher.setRequestContentType = "application/json";

        if (url_opcional != undefined)
            dataSearcher.setUrl = url_opcional;
        else
            dataSearcher.setDataLink = "#SESION-INDEX-URL";

        dataSearcher.setOutputTarget = "#grill";
        //params
        let sucursal = $("#SUCURSAL").val();
        let estado = $("#ESTADO").val();
        let usuario = $("#USER-ID").val();
        let F_desde = $("#FECHA_DESDE").val();
        let F_hasta = $("#FECHA_HASTA").val();
        let cajero = $('#CAJERO').val();


        let paramsr = {
            ESTADO: estado
        };
        if (sucursal != undefined) paramsr.SUCURSAL = sucursal;
        if (usuario != undefined) paramsr.USUARIO = usuario;
        if (F_desde != "") paramsr.FECHA_DESDE = F_desde;
        if (F_hasta != "") paramsr.FECHA_HASTA = F_hasta;
        if (cajero != "" && $("#F_CAJERO").val() == "S") paramsr.USUARIO = cajero; //Cajero - usuario de caja


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
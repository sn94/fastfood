<?php


$MODULO_FLAG=    isset($_GET['m']) ? $_GET['m'] :  "";
$QUERY_FLAG=  $MODULO_FLAG!= "c"  ? ""  :    "?m=$MODULO_FLAG";


//Plantilla
$templateName = "";

if (  $MODULO_FLAG !=  "c") :
    $templateName = "templates.admin.index";
elseif ( $MODULO_FLAG == "c") :
    $templateName = "templates.caja.index";
endif;
?>



@extends( $templateName)

@section("PageTitle")
Clientes
@endsection


@section("content")



 

<input type="hidden" id="GRILL-URL" value="{{url('clientes'.$QUERY_FLAG)}}">
<input type="hidden" id="GRILL-URL-CUSTOM" value="{{url('clientes/buscar'.$QUERY_FLAG)}}">






<div class="container-fluid mt-1 fast-food-bg   col-12 col-md-12 col-lg-10 col-xl-8 pb-5">
    <h3 class="fast-food-big-title" >Ficha de Clientes</h3>

    <div id="loaderplace"></div>

    <x-search-report-downloader placeholder="BUSCAR POR DESCRIPCION" callback="buscarClientes()">
        <a class="btn fast-food-form-button" href="<?= url("clientes/create".$QUERY_FLAG) ?>"> NUEVO</a>

        <div class="pr-2" style="display: flex;flex-direction: row;justify-content: flex-end;align-items: baseline;">
            <label style="font-weight: 600; ">POR CIUDAD:</label>
            <x-city-chooser onChange="buscarClientes()" name="CIUDAD" clase="form-control" style="padding: 0px !important; font-size: 13px !important;background: white !important;height: 25px !important;" value="" />
        </div>
    </x-search-report-downloader>
    <div class="mt-2" id="grill" style="min-height: 300px;">

    </div>
</div>


<script>


     

    async function fill_grill(url_optional) {


        let grill_url = $("#GRILL-URL-CUSTOM").val();
        if (url_optional != undefined) {
            url_optional.preventDefault();
            grill_url = url_optional.currentTarget.href;
        }


        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#grill").html(loader);
        let req = await fetch(grill_url, {

            headers: {
                'X-Requested-With': "XMLHttpRequest",
                Pragma: "no-cache",
                "Cache-Control": "max-age=0"
            }
        });
        let resp = await req.text();
        $("#grill").html(resp);


    }






    function buscarClientes() {
        //PARMETROS
        let buscado = $("#search").val();
        let ciudad_id = $("select[name=CIUDAD]").val();
        let parametros = {
            buscado: buscado,
            CIUDAD: ciudad_id
        };

        dataSearcher.setDataLink = "#GRILL-URL-CUSTOM";
        dataSearcher.setOutputTarget = "#grill";
        dataSearcher.setParametros = parametros;
        dataSearcher.formatoHtml();
    }



    var dataSearcher = undefined;

    window.onload = function() {
        dataSearcher = new DataSearcher();
        buscarClientes();
       // fill_grill();
    }
</script>
@stop



@section("jsScripts")


<script src="<?= url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= url("assets/xls_gen/xls_ini.js?v=" . rand(0.0, 100)) ?>"></script>
<script src="<?= url("assets/js/buscador.js?v=" . rand(0.0, 100)) ?>"></script>


@endsection
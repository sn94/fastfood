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



@section("PageTitle")
Stock
@endsection

@section("content")



<input type="hidden" id="GRILL-URL" value="{{url('stock')}}">
<input type="hidden" id="GRILL-URL-CUSTOM" value="{{url('stock/buscar')}}">









<div id="loaderplace"></div>

<div class="container col-12 col-md-12 col-lg-9 bg-dark text-light">

<h2 class="text-center mt-2"  > Fichas de Stock</h2>


    <x-search-report-downloader placeholder="BUSCAR POR CÓDIGO DE BARRA O DESCRIPCION" callback="buscarStock()">
        @if(session("NIVEL") == "SUPER")
        <a class="btn btn-sm btn-warning mb-2 mr-0 ml-0 mr-md-5 ml-md-5" href="<?= url("stock/create".$QUERY_FLAG) ?>"> NUEVO</a>
        @endif
        <label style="display: flex;flex-direction: row; color: white;">Filtrar por:

            <x-tipo-stock-chooser id="TIPO-STOCK" :value="$TIPO" callback="buscarStock('tipo_stock')" style="height: 25px;"></x-tipo-stock-chooser>

        </label>


        <label style="display: flex;flex-direction: row; color: white;"> Familia:
            <x-familia-stock-chooser id="FAMILIA-STOCK" name="" value="" callback="buscarStock('familia')" style="height: 25px;"></x-familia-stock-chooser>

        </label>
        <input type="hidden" id="DESCRIPCION-ORDER" value="ASC">
        <input type="hidden" id="PVENTA-ORDER" value="">


    </x-search-report-downloader>

    <div id="grill" class="mr-0 ml-0" style="min-height: 300px;"></div>
</div>


<script>
    function show_loader() {
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
    }

    function hide_loader() {
        $("#loaderplace").html("");
    }




    function ordenarDescripcion(sentido) {
        /*ASC  DESC */

        $("#DESCRIPCION-ORDER").val(sentido);
        $("#PVENTA-ORDER").val("");
        buscarStock();
    }


    function ordenarPventa(sentido) {
        /*ASC  DESC */

        $("#PVENTA-ORDER").val(sentido);
        $("#DESCRIPCION-ORDER").val("");
        buscarStock();
    }




    function buscarStock(tipo) {
        //PARMETROS
        let buscado = $("#search").val();
        let tipo_filtro = tipo == undefined ? "patron" : tipo;
        //valores de filtros
        let tipoStock = $("#TIPO-STOCK").val();
        let familia = $("#FAMILIA-STOCK").val();;

        //Ordenacion de registros 
        let orden_descr = $("#DESCRIPCION-ORDER").val();
        let orden_pventa = $("#PVENTA-ORDER").val();

        let parametros = {
            buscado: buscado,
            orden: {
                "DESCRIPCION": orden_descr,
                "PVENTA": orden_pventa
            }
        };
        //elegir filtro
        if (tipo_filtro == "tipo_stock")
            parametros.tipo = tipoStock;
        if (tipo_filtro == "familia")
            parametros.familia = familia;


        dataSearcher.setRequestContentType = "application/json";
        dataSearcher.setDataLink = "#GRILL-URL-CUSTOM";
        dataSearcher.setOutputTarget = "#grill";
        dataSearcher.setParametros = parametros;
        dataSearcher.formatoHtml();
    }



    var dataSearcher = undefined;

    window.onload = function() {
        dataSearcher = new DataSearcher();
        buscarStock();
    }
</script>
@endsection


@section("jsScripts")


<script src="<?= url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= url("assets/xls_gen/xls_ini.js?v=" . rand(0.0, 100)) ?>"></script>
<script src="<?= url("assets/js/buscador.js?v=" . rand(0.0, 100)) ?>"></script>


@endsection
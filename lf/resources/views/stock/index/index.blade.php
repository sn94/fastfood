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




@extends( $templateName)

@section("PageTitle")
Stock
@endsection

@section("content")



<input type="hidden" id="GRILL-URL" value="{{url('stock')}}">
<input type="hidden" id="GRILL-URL-CUSTOM" value="{{url('stock/buscar').$QUERY_FLAG}}">









<div id="loaderplace"></div>

<div class="container mt-1 col-12 col-md-12 col-lg-9 fast-food-bg   pb-5">

    <h3 class="fast-food-big-title"> Fichas de Stock</h3>


    <x-search-report-downloader placeholder="BUSCAR POR CÓDIGO DE BARRA O DESCRIPCION" callback="buscarStock()">
 
            <div class="container-fluid m-0 p-0 d-flex flex-row flex-wrap ">
                @if( $MODULO_FLAG != "c")
                <div class=" mb-1 col-md-1">
                    <a class="btn fast-food-form-button " href="<?= url("stock/create" . $QUERY_FLAG) ?>"> NUEVO</a>
                </div>
                @endif

                <div class="d-flex flex-row col-12 col-md-6 col-lg-4">

                    <label style="width: 150px;">Filtrar por:</label>

                    <x-pretty-radio-button callback="buscarStock()" id="F_TIPO" name="FILTRO" value="TIPO" label="" checked="si"></x-pretty-radio-button>
                    <x-tipo-stock-chooser id="TIPO-STOCK" :value="$TIPO" checked="true" i callback="buscarStock()" style="height: 25px;" class="form-control-sm"></x-tipo-stock-chooser>
                </div>


                <div class="d-flex flex-row  col-12 col-md-6 col-lg-4">
                    <label class="ms-2 w-100"> Familia:
                    </label>
                    <x-pretty-radio-button callback="buscarStock();" id="F_FAMILIA" name="FILTRO" value="FAMILIA" label="" checked="no"></x-pretty-radio-button>
                    <x-familia-stock-chooser id="FAMILIA-STOCK" name="" value="" callback="buscarStock()" style="height: 25px;" class="form-control-sm"></x-familia-stock-chooser>


                </div>
                <input type="hidden" id="DESCRIPCION-ORDER" value="ASC">
                <input type="hidden" id="PVENTA-ORDER" value="">
            </div>
    

    </x-search-report-downloader>

    <div id="grill" class="mr-0 ml-0" style="min-height: 300px;"></div>
</div>


<script>
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




    function buscarStock(urlOpcional) {
        if (urlOpcional && typeof urlOpcional == "object" && "currentTarget" in urlOpcional)
            urlOpcional.preventDefault();

        //PARMETROS
        let buscado = $("#search").val();
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

        let filtroTipo = Array.prototype.filter.call(document.querySelectorAll("input[name=FILTRO]"), ar => ar.checked)[0].value; //  $("input[name=FILTRO]").val();
        if (filtroTipo == "TIPO")
            parametros.tipo = tipoStock;
        if (filtroTipo == "FAMILIA")
            parametros.familia = familia;

        dataSearcher.setRequestContentType = "application/json";
        if (urlOpcional)
            dataSearcher.setUrl = urlOpcional.currentTarget.href;
        else
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
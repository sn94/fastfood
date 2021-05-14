<?php

$MODULO_FLAG =    isset($_GET['m']) ? $_GET['m'] :  "";
$QUERY_FLAG =  $MODULO_FLAG == "c" ? "?m=c"  :  "";
//Plantilla
$templateName = "";
$index = "";

if ($MODULO_FLAG !=  "c") :
  $templateName = "templates.admin.index";

elseif ($MODULO_FLAG  ==  "c") :
  $templateName = "templates.caja.index";
endif;

$index = url("ventas/filtrar");
?>



@extends( $templateName)

@section("PageTitle")
Ventas
@endsection

@section("content")


<div id="loaderplace"></div>
<div class="container-fluid  col-12 col-md-12 col-lg-10 bg-dark  mt-2 text-light pb-5  mb-5 ">

  <h3 class="text-center">Ventas</h3>
  <div class="row">
    @include("ventas.reportes.graficos.grafico1")
    @include("ventas.reportes.graficos.grafico2") 
    @include("ventas.reportes.graficos.grafico3") 
  </div>

  <div class=" col-12 col-md-5 pb-0">
    <label> Filtro: </label>
    <select class="form-control form-control-sm" id="FILTRO" onchange="formularioDeFiltro(event)">
      <option value="1">PRODUCTOS MÁS VENDIDOS POR SUCURSALES</option>
      <option value="2">OTROS PARÁMETROS</option>
      
    </select>
  </div>

  <x-search-report-downloader showSearcherInput="N" placeholder="" callback="buscarVentas()">
    <x-slot name="top_panel">
      @include("ventas.reportes.filters.filter".$FILTRO)
    </x-slot>
  </x-search-report-downloader>

  <div class="container-fluid p-0 pt-2 pb-2" id="grill">
    @include("ventas.reportes.views.filter".$FILTRO)
  </div>


</div>
<script>
   


  async function buscarVentas(params) {
    let parametros = params;
    let grill_url = "<?= $index ?>" + "<?= $QUERY_FLAG ?>";
    dataSearcher.setUrl = grill_url;
    dataSearcher.setOutputTarget = "#grill";
    dataSearcher.setParametros = parametros;
    show_loader();
    dataSearcher.formatoHtml();
  }



  async function formularioDeFiltro(ev) {
    let nroFiltro = ev.target.value;
    let url__= "<?= $index ?>/" + nroFiltro + "<?= $QUERY_FLAG ?>";
    let req = await fetch(  url__, {

      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
    });
    let resp = await req.text();
    $("#search-report-downloader-top-panel").html(resp);

    filtrar();

  }

  function inicializar() {
    let nroFiltro = $("#FILTRO").val();
    let grill_url = "<?= $index ?>" + "<?= $QUERY_FLAG ?>";
    dataSearcher.setUrl = grill_url;
    dataSearcher.setMetodo = "POST";
    dataSearcher.setOutputTarget = "#grill";
    dataSearcher.setParametros = {
      FILTRO: nroFiltro
    };
    //dataSearcher.formatoHtml();
  }


  var dataSearcher = undefined;

  window.onload = function() {
    dataSearcher = new DataSearcher();
    inicializar();
   grafProductosMasVendidos(); 
   grafMediosDePagoPreferido();
   grafRecaudacionesUltimosSeisMeses();
  }
</script>


@endsection


@section("jsScripts")


<script src="<?= url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= url("assets/xls_gen/xls_ini.js?v=" . rand(0.0, 100)) ?>"></script>
<script src="<?= url("assets/js/buscador.js?v=" . rand(0.0, 100)) ?>"></script>
<!--generador de graficos -->
<script src="<?= url("assets/js/chart.js?v=" . rand(0.0, 100)) ?>"></script>
<script src="<?= url("assets/js/chartHelper.js?v=" . rand(0.0, 100)) ?>"></script>


@endsection
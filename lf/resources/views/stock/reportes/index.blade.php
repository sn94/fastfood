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

$index = url("stock/filtrar");
?>



@extends( $templateName)

@section("PageTitle")
Stock
@endsection

@section("content")


<div id="loaderplace"></div>
<div class="container-fluid  col-12 col-md-12 col-lg-10 fast-food-bg  mt-2   mb-5 pb-5">

  <h3 class="fast-food-big-title" >Stock</h3>


  <div class=" container pb-0">
    <label> Filtro: </label>
    <select class="form-control form-control-sm" id="FILTRO" onchange="formularioDeFiltro(event)">
      <option value="1">PRODUCTOS MÁS PEDIDOS POR SUCURSALES</option>
      <option value="2">PRODUCTOS CON MÁS RESIDUOS</option>
      <option value="3_1">INGREDIENTES MÁS UTILIZADOS POR COCINA</option>
      <option value="3_2">PRODUCTOS QUE MÁS SE PREPARAN</option>
      <option value="4">EXISTENCIAS</option>
      <option value="5">SALIDAS SEGÚN DESTINO</option>
    </select>
  </div>

  <x-search-report-downloader showSearcherInput="N" placeholder="" callback="buscarStock()">
    <x-slot name="top_panel">
      @include("stock.reportes.filters.filter".$FILTRO)
    </x-slot>
  </x-search-report-downloader>

  <div class="container-fluid p-0 pt-2 pb-2" id="grill">
    @include("stock.reportes.views.filter".$FILTRO)
  </div>
  <div class="row">
    @include("stock.reportes.graficos.grafico1")
    @include("stock.reportes.graficos.grafico2")
    @include("stock.reportes.graficos.grafico3")
  </div>


</div>
<script>
  


  async function buscarStock(params) {
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
    let req = await fetch("<?= $index ?>/" + nroFiltro, {

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
    grafProductosMasPedidos();
    grafProductosMasPedidosLocalmente();
    grafResiduosDeIngredientes();
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
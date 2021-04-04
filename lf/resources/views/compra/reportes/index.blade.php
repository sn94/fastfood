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


$index = url("compra/filtrar");
?>



@extends( $templateName)


@section("PageTitle")
Compras
@endsection

@section("content")


@include("ventas.proceso.res.impresion")


<div id="loaderplace"></div>
<div class="container-fluid  col-12 col-md-12 col-lg-10 bg-dark  mt-2 text-light ">

  <h3 class="text-center">Reportes & Compras</h3>


  <div class=" col-12 col-md-5 pb-0">
    <label> Filtro: </label>
    <select class="form-control form-control-sm" id="FILTRO" onchange="formularioDeFiltro(event)">
      <option value="1">COSTOS DE PRODUCTO POR TIPO Y PROVEEDOR</option>
     
     <!-- <option value="2">CONTROL DE COSTOS vs PRECIO DE VENTA</option>
      <option value="3">COMPRAS ENTRE FECHAS POR PROVEEDORES</option>
      <option value="4">COMPRAS POR PRODUCTOS Y POR FECHA</option>
      <option value="5">RESUMEN DE COMPRA POR PROVEEDOR</option>
      <option value="6">RESUMEN DE COMPRA POR PROVEEDOR Y PRODUCTO</option>
-->
      <option value="2">PRODUCTOS MÁS COMPRADOS</option>
      <option value="3">COMPARATIVO DE COMPRAS POR SUCURSAL</option>
    </select>
  </div>

  <x-search-report-downloader showSearcherInput="N" placeholder="" callback="buscarCompras()">
 

    @include("compra.reportes.filters.filter".$FILTRO)
  





  </x-search-report-downloader>


  <div class="container-fluid pt-2 pb-2" id="grill">


    @include("compra.reportes.views.filter".$FILTRO)
    

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



  async function buscarCompras( params) {
  show_loader();
    let parametros = params;
    let grill_url = "<?= $index ?>"+ "<?= $QUERY_FLAG ?>";

    dataSearcher.setUrl = grill_url; 
    dataSearcher.setOutputTarget = "#grill";
    dataSearcher.setParametros = parametros;
    dataSearcher.formatoHtml();


  }



  async function formularioDeFiltro(ev) {
    let nroFiltro = ev.target.value;
    let req = await fetch("<?= url('compra/filtrar') ?>/" + nroFiltro, {

      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
    });
    let resp = await req.text();
    $("#search-report-downloader-center-panel").html(resp);

    filtrar();

  }

  function inicializar() {
    let nroFiltro = $("#FILTRO").val();
    let grill_url = "<?= $index ?>" + "<?= $QUERY_FLAG ?>";
    dataSearcher.setUrl = grill_url;
    dataSearcher.setMetodo = "POST";
    dataSearcher.setOutputTarget = "#grill";
    dataSearcher.setParametros = { FILTRO: nroFiltro};
    //dataSearcher.formatoHtml();
  }


  var dataSearcher = undefined;

  window.onload = function() {
    dataSearcher = new DataSearcher();
    inicializar();
  }
</script>


@endsection


@section("jsScripts")


<script src="<?= url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= url("assets/xls_gen/xls_ini.js?v=" . rand(0.0, 100)) ?>"></script>
<script src="<?= url("assets/clases/buscador.js?v=" . rand(0.0, 100)) ?>"></script>


@endsection
<?php


?>
@extends("templates.caja.index")

@section("content")


@include("ventas.proceso.impresion")


<div class="container-fluid fast-food-bg   col-12 col-lg-10  pb-5 mt-2 ">
  <h3 class="fast-food-big-title" >Ventas diarias</h3>


  <x-search-report-downloader showSearcherInput="N" placeholder="" callback="fill_grill()">
    <label>Desde: <input onchange="fill_grill()" type="date" id="FECHA_DESDE">
      <label>Hasta: <input onchange="fill_grill()" type="date" id="FECHA_HASTA">
  </x-search-report-downloader>

  <div class="container-fluid p-0" id="grill">
    @include("ventas.index.grill")
  </div>

</div>

<script>
  async function fill_grill(ev) {
    if (ev != undefined && typeof ev == "object") 
      ev.preventDefault();
    prepararBusqueda(ev);
    dataSearcher.formatoHtml();
  }
  async function prepararBusqueda(ev) {
    if (!("dataSearcher" in window))
      window.dataSearcher = new DataSearcher();
    //configurar objeto

    let fecha_desde = $("#FECHA_DESDE").val();
    let fecha_hasta = $("#FECHA_HASTA").val();
    let parametros = {};
    if (fecha_desde != "" && fecha_hasta != "") {
      parametros.FECHA_DESDE = fecha_desde;
      parametros.FECHA_HASTA = fecha_hasta;
    }

    //Determinar link
    let page_index = 1;
    //prevenir propagacion
    if (ev != undefined && typeof ev == "object") {
      ev.preventDefault();
      let url_parts = ev.target.href.split("?");
      if (url_parts.length > 1) page_index = url_parts[1].split("=")[1];
    }

    let urlDeBusqueda = "<?= url('ventas/index') ?>?page=" + page_index;

    dataSearcher.setUrl = urlDeBusqueda;
    dataSearcher.setOutputTarget = "#grill";
    dataSearcher.setParametros = parametros;

  }


  window.onload = function() {
    prepararBusqueda();

  };
</script>


@endsection

@section("jsScripts")


<script src="<?= url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= url("assets/xls_gen/xls_ini.js?v=" . rand(0.0, 100)) ?>"></script>
<script src="<?= url("assets/js/buscador.js?v=" . rand(0.0, 100)) ?>"></script>


@endsection
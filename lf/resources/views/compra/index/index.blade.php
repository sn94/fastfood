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

$create =  url("compra".$QUERY_FLAG);
$index = url("compra/index".$QUERY_FLAG);
?>



@extends( $templateName)


@section("PageTitle")
Compras
@endsection

@section("content")


@include("ventas.proceso.res.impresion")



<div class="container-fluid  col-12 col-md-12 col-lg-12 col-xl-10 bg-dark  mt-lg-2 mt-md-0 text-light pb-5 ">

  <h3 class="text-center">Compras</h3>
  <div class="container-fluid mb-1">
    <a class="btn btn-warning" href="{{$create}}">NUEVO</a>
  </div>





  <x-search-report-downloader showSearcherInput="N" placeholder="" callback="buscarCompras()">

 
      <div  class="pb-0 row">
        <x-pretty-checkbox callback="buscarCompras()" id="TODO" name="" value="S" label="Todos" onValue="S" offValue="N"></x-pretty-checkbox>

        <div class="col-6 col-md pb-0">
          <label> Desde: </label>
          <input class="form-control form-control-sm" type="date" id="FECHA_DESDE" onchange="buscarCompras('FECHA_DESDE')">
        </div>

        <div class="col-6 col-md pb-0">
          <label> Hasta:</label>
          <input class="form-control form-control-sm" type="date" id="FECHA_HASTA" onchange="buscarCompras('FECHA_HASTA')">
        </div>

        @if( session('NIVEL') == 'SUPER' ||  session("NIVEL") == "GOD" )
        <div class="col-6 col-md pb-0">
          <label>
            Sucursal:
          </label>
          <x-sucursal-chooser class="form-control form-control-sm" name="SUCURSAL" value="" id="SUCURSAL" callback="buscarCompras('SUCURSAL')" style=""></x-sucursal-chooser>
        </div>
        @endif


        <div class="col-6 col-md pb-0">
          <label>
            Forma de pago:
          </label>
          <x-forma-pago-chooser callback="buscarCompras('FORMA_PAGO')" class="form-control form-control-sm" style="" name="" value="" id="FORMA_PAGO"></x-forma-pago-chooser>
        </div>

        <div class="col  pb-0">
          <label>
            Proveedor:
          </label>
          <x-proveedor-chooser callback="buscarCompras('PROVEEDOR')" class="form-control form-control-sm" style="" name="" value="" id="PROVEEDOR"></x-proveedor-chooser>
        </div> 
      </div> 



  </x-search-report-downloader>


  <div class="container-fluid pt-2 pb-2" id="grill">

    @include("compra.index.grill")
  </div>


</div>
<script>

  async function buscarCompras(FILTRO) {

    let grill_url = "<?= $index ?>";

    //SUCURSAL
    let sucursal = $("#SUCURSAL").val();

    //FECHA_DESDE
    let fecha_desde = $("#FECHA_DESDE").val();
    //FECHA_HASTA
    let fecha_hasta = $("#FECHA_HASTA").val();
    //FORMA_PAGO
    let forma_pago = $("#FORMA_PAGO").val();
    //PROVEEDOR
    let proveedor = $("#PROVEEDOR").val();

    let parametros = {};
    if ($("#TODO").val() != "S") {
      if (FILTRO == "SUCURSAL" && sucursal != undefined) parametros.SUCURSAL = sucursal;
      if (FILTRO == "FECHA_DESDE" && fecha_desde != undefined) {
        parametros.FECHA_DESDE = fecha_desde;
        parametros.FECHA_HASTA = fecha_hasta;
      }
      if (FILTRO == "FECHA_HASTA" && fecha_hasta != undefined) {
        parametros.FECHA_DESDE = fecha_desde;
        parametros.FECHA_HASTA = fecha_hasta;
      }
      if (FILTRO == "FORMA_PAGO" && forma_pago != undefined) parametros.FORMA_PAGO = forma_pago;
      if (FILTRO == "PROVEEDOR" && proveedor != undefined) parametros.PROVEEDOR = proveedor;

    }

    dataSearcher.setUrl = grill_url;
    dataSearcher.setOutputTarget = "#grill";
    dataSearcher.setParametros = parametros;
    dataSearcher.formatoHtml();

  }




  async function delete_row(ev) {
    ev.preventDefault();
    if (!confirm("continuar?")) return;
    let req = await fetch(ev.currentTarget.href, {
      "method": "DELETE",
      headers: {

        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      body: "_method=DELETE&_token=" + $('meta[name="csrf-token"]').attr('content')

    });
    let resp = await req.json();
    if ("ok" in resp) buscarCompras();
    else alert(resp.err);

  }

  var dataSearcher = undefined;

  window.onload = function() {
    dataSearcher = new DataSearcher();
    buscarCompras();
  }
</script>


@endsection


@section("jsScripts")


<script src="<?= url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= url("assets/xls_gen/xls_ini.js?v=" . rand(0.0, 100)) ?>"></script>
<script src="<?= url("assets/js/buscador.js?v=" . rand(0.0, 100)) ?>"></script>


@endsection
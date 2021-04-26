@extends("templates.admin.index")

@section("PageTitle")
Proveedores
@endsection

@section("content")




<input type="hidden" id="GRILL-URL" value="{{url('proveedores')}}">
<input type="hidden" id="GRILL-URL-CUSTOM" value="{{url('proveedores/buscar')}}">



<div class="container-fluid bg-dark text-light col-12 col-md-12 col-lg-10 col-xl-8 pb-5 mb-5">

    <h2 class="text-center mt-2">Ficha de Proveedores</h2>

    <div id="loaderplace"></div>
    <x-search-report-downloader placeholder="BUSCAR POR DESCRIPCION" callback="buscarProveedores()">
        <a class="btn btn-sm btn-warning" href="<?= url("proveedores/create") ?>"> NUEVO</a>

        <div class="pr-2" style="display: flex;flex-direction: row;justify-content: flex-end;align-items: baseline;">
            <label style="font-weight: 600; color: white;">POR CIUDAD:</label>
            <x-city-chooser onChange="buscarProveedores()" name="CIUDAD" clase="form-control" style="padding: 0px !important; font-size: 13px !important;color: white;background: white !important;color: black !important;height: 25px !important;" value="" />
        </div>
    </x-search-report-downloader>


    <div id="grill" style="min-height: 300px;">

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


    function inicializarBuscador() {
      
        dataSearcher.setDataLink = "#GRILL-URL-CUSTOM";
        dataSearcher.setOutputTarget = "#grill";
      
        buscarProveedores();
    }


    function buscarProveedores() {
        //PARMETROS
        let buscado = $("#search").val();
        let ciudad_id = $("select[name=CIUDAD]").val();
        let parametros = {
            buscado: buscado,
            CIUDAD: ciudad_id
        };
        dataSearcher.setParametros = parametros;
        dataSearcher.formatoHtml();
    }





    async function fill_grill(url_optional) {

        let grill_url = $("#GRILL-URL").val();
        if (url_optional != undefined) {
            url_optional.preventDefault();
            grill_url = url_optional.currentTarget.href;
        }


        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#grill").html(loader);
        let req = await fetch(grill_url, {

            headers: {
                'X-Requested-With': "XMLHttpRequest"
            }
        });
        let resp = await req.text();
        $("#grill").html(resp);

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
        inicializarBuscador(); 
        //   fill_grill();
    }
</script>
@endsection
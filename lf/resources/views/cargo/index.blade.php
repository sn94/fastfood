@extends("templates.admin.index")


@section("PageTitle")
Cargos
@endsection

@section("content")
 

<input type="hidden" id="GRILL-URL" value="{{url('cargo')}}">
<input type="hidden" id="GRILL-URL-CUSTOM" value="{{url('cargo/buscar')}}">






<div class="container col-12 col-md-6 bg-dark text-light">
    <h3 class="text-center mt-2"  >Ficha de Cargos</h3>



    <div id="loaderplace"></div>


    <div id="form" class="mb-2">
        @include("cargo.create")
    </div>


     
    <x-search-report-downloader placeholder="BUSCAR POR DESCRIPCION" callback="buscarCargos()" />

    <div class="m-0" id="grill" style="min-height: 300px;">

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




    async function buscar(esto) {
        let termino = esto.value;
        let grill_url = $("#GRILL-URL-CUSTOM").val();
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#grill").html(loader);
        let req = await fetch(grill_url, {

            method: "POST",
            headers: {
                'X-Requested-With': "XMLHttpRequest",
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: "buscado=" + termino
        });
        let resp = await req.text();
        $("#grill").html(resp);
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


    function buscarCargos() {
        //PARMETROS
        let buscado = $("#search").val();
        let parametros = {
            buscado: buscado
        };

        dataSearcher.setDataLink = "#GRILL-URL-CUSTOM";
        dataSearcher.setOutputTarget = "#grill";
        dataSearcher.setParametros = parametros;
        dataSearcher.formatoHtml();
    }


    var dataSearcher = undefined;

    window.onload = function() {
        dataSearcher = new DataSearcher();
        buscarCargos();
      //  fill_grill();
    }
</script>
@endsection


@section("jsScripts")


<script src="<?= url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= url("assets/xls_gen/xls_ini.js?v=" . rand(0.0, 100)) ?>"></script>
<script src="<?= url("assets/js/buscador.js?v=" . rand(0.0, 100)) ?>"></script>


@endsection
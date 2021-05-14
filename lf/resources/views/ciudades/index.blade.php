@extends("templates.admin.index")



@section("PageTitle")
Ciudades
@endsection
@section("content")

 
<input type="hidden" id="GRILL-URL" value="{{url('ciudades')}}">
<input type="hidden" id="GRILL-URL-CUSTOM" value="{{url('ciudades/buscar')}}">






<div class="container col-12 col-md-5 bg-dark">
    <h3 class="text-center mt-2 text-light" >Ficha de Ciudades</h3>



    <div id="loaderplace"></div>


    <div id="form">
        @include("ciudades.create")
    </div>


   
    <x-search-report-downloader placeholder="BUSCAR POR DESCRIPCION DE FAMILIA" callback="buscarCiudades()" />


    <div id="grill" style="min-height: 300px;">

    </div>
</div>


<script>
    


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


    function buscarCiudades() {
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
        fill_grill();
    }
</script>
@endsection


@section("jsScripts")


<script src="<?= url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= url("assets/xls_gen/xls_ini.js?v=" . rand(0.0, 100)) ?>"></script>
<script src="<?= url("assets/js/buscador.js?v=" . rand(0.0, 100)) ?>"></script>


@endsection
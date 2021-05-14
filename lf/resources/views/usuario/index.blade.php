@extends("templates.admin.index")

@section("PageTitle")
Usuarios
@endsection
@section("content")

 
<input type="hidden" id="GRILL-URL" value="{{url('usuario')}}">
<input type="hidden" id="GRILL-URL-CUSTOM" value="{{url('usuario/buscar')}}">

<div class="container-fluid col-12 col-md-10 bg-dark text-light">
<h2 class="text-center mt-2" >Fichas de usuarios</h2>

<div id="loaderplace"></div>
 
<x-search-report-downloader placeholder="BUSCAR POR NOMBRE DE USUARIO, O NOMBRE-APELLIDO" callback="buscarUsuarios()" >
<a class="btn btn-sm btn-warning" href="<?= url("usuario/create") ?>"> NUEVO</a>

</x-search-report-downloader>
<div id="grill"  style="min-height: 300px;"> 
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

    async function fill_grill(  url_optional) {

       
        let grill_url = $("#GRILL-URL").val();
        if(  url_optional  !=  undefined ) { 
            url_optional.preventDefault();
             grill_url=  url_optional.currentTarget.href;
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


   

    function buscarUsuarios() {
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
        buscarUsuarios();
        //fill_grill();
    }
</script>
@endsection

@section("jsScripts")


<script src="<?= url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= url("assets/xls_gen/xls_ini.js?v=" . rand(0.0, 100)) ?>"></script>
<script src="<?= url("assets/js/buscador.js?v=" . rand(0.0, 100)) ?>"></script>


@endsection
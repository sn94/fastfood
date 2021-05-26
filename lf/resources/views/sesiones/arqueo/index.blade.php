@extends("templates.caja.index")


@section("PageTitle")
Arqueo de caja {{date('d-m-Y')}}
@endsection


@section("content")

<style>
    table thead tr th,
    table tbody tr td,
    table tfoot tr td {
        padding: 0px !important;
        color: black !important;

    }
</style>


<div id="loaderplace">
</div>
<div class="container col-12 col-md-10 col-lg-7 bg-dark mt-0 mb-0  mt-md-0 mb-md-0 mt-lg-3 mb-lg-3 pb-5">

<input type="hidden" value="<?= url("sesiones/informe-arqueo/" . session("SESION")) ?>"  id="INFORME-LINK">
    <button onclick="guardar()" id="BOTON-GUARDAR" class="btn btn-danger btn-sm" type="button">CERRAR SESIÓN</button>
    <button id="BOTON-ARQUEO" onclick="imprimirArqueo()" class="btn btn-success btn-sm d-none" type="button">GENERAR INFORME DE ARQUEO</button>
    @include("sesiones.arqueo.form")


</div>


<script>
    async function guardar() {
        //config_.processData= false; config_.contentType= false;
        
        $("#BOTON-GUARDAR").prop("disabled", true);
        show_loader();
        let req = await fetch(  "<?=url("sesiones/cerrar/".$SESION->REGNRO)?>" , {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: ""
        });
        let resp = await req.json();
        hide_loader();
        if ("ok" in resp) {
            alert(resp.ok);
            $("#BOTON-ARQUEO").removeClass("d-none");
            $("#BOTON-GUARDAR").addClass("d-none");

            //Index sesiones limitada a listar solo las propias
            window.location = "<?= url("sesiones?m=c") ?>";

        } else {
            $("#BOTON-GUARDAR").removeClass("d-none");
            $("#BOTON-GUARDAR").prop("disabled", false);
            $("#BOTON-ARQUEO").addClass("d-none");
            alert(resp.err);
        }
    }


 

    // Imprimir informe de arqueo
    function imprimirArqueo() {
        dataSearcher.setMetodo= "GET";
        dataSearcher.setDataLink =  "#INFORME-LINK" ;
        dataSearcher.formatoPdf();
    }

    var dataSearcher= undefined;

    window.onload= function(){
        dataSearcher=  new DataSearcher();
    }
</script>
@endsection

@section("jsScripts")
<script src="<?= url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= url("assets/xls_gen/xls_ini.js?v=" . rand(0.0, 100)) ?>"></script>
<script src="<?= url("assets/js/buscador.js?v=" . rand(0.0, 100)) ?>"></script>
@endsection
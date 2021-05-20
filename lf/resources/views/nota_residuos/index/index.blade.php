 @extends( "templates.admin.index")

 @section("PageTitle")
 Notas de Residuo
 @endsection


 @section("content")

 <div class="container-fluid col-12 col-md-12 col-lg-10 col-xl-8 bg-dark pb-5">
     <h2 class="text-center mt-2 text-light">Notas de residuo</h2>

     <div id="loaderplace"></div>

     <a class="btn btn-warning mb-1" href="{{url('nota-residuos/create')}}">NUEVA NOTA DE RESIDUO</a>


     <x-search-report-downloader placeholder="BUSCAR POR DESCRIPCION" callback="buscarNotaResiduos()" showSearcherInput="N">
       
   
     <div class="row pt-1 text-light w-100">


<div class="col-12  col-md-3  pb-0">
        <label> Desde: </label>
        <input class="form-control form-control-sm" type="date" id="FECHA_DESDE" onchange="buscarNotaResiduos()">
    </div>

    <div class="col-12   col-md-3   pb-0">
        <label> Hasta:</label>
        <input class="form-control form-control-sm" type="date" id="FECHA_HASTA" onchange="buscarNotaResiduos()">
    </div>
     </div>

   
    </x-search-report-downloader>

     <div class="mt-2" id="grill">

         @include("nota_residuos.index.grill")
     </div>
 </div>

 @endsection


 @section("jsScripts")


 <script src="<?= url("assets/xls_gen/xls.js") ?>"></script>
 <script src="<?= url("assets/xls_gen/xls_ini.js?v=" . rand(0.0, 100)) ?>"></script>
 <script src="<?= url("assets/js/buscador.js?v=" . rand(0.0, 100)) ?>"></script>

 <script>
     async function fill_grill(url_optional) {


         let grill_url = "<?=url('nota-residuos/index')?>";

         if (url_optional != undefined) {
             url_optional.preventDefault();
             grill_url = url_optional.currentTarget.href;
         }

         show_loader();
         

         let req = await fetch(grill_url, {

             headers: {
                 'X-Requested-With': "XMLHttpRequest"
             }
         });
         let resp = await req.text();

         $("#grill").html(resp);

         hide_loader();

     }

 
     
     function buscarNotaResiduos() {
        //PARMETROS
        
        let F_desde = $("#FECHA_DESDE").val();
        let F_hasta = $("#FECHA_HASTA").val();
     
        let parametros = { };
        if( F_desde  != "")   parametros.FECHA_DESDE=  F_desde;
        if( F_hasta  != "")   parametros.FECHA_HASTA=  F_hasta;


        dataSearcher.setUrl=  "<?=url('nota-residuos/index')?>";
        dataSearcher.setOutputTarget = "#grill";
        dataSearcher.setParametros = parametros;
        dataSearcher.formatoHtml();
    }






    var dataSearcher = undefined;

    window.onload = function() {
        dataSearcher = new DataSearcher();
        buscarNotaResiduos();
       // fill_grill();
    }
 </script>


 @endsection
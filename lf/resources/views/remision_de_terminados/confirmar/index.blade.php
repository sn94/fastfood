 @extends( "templates.admin.index")

 @section("PageTitle")
 Notas de remisión de productos elaborados
 @endsection


 @section("content")


 <x-fast-food-modal id="NOTA-REMISION-MODAL" title="Detalles" />
 


 <div class="container-fluid col-12 col-md-12 col-lg-10 col-xl-8 bg-dark pb-5">
     <h2 class="text-center mt-2 text-light"> Notas de remisión de productos elaborados </h2>

     <div id="loaderplace"></div>



     <x-search-report-downloader placeholder="BUSCAR POR DESCRIPCION" callback="buscarNotaRemision()" showSearcherInput="N">

        
         <div class="row pt-1 text-light w-100">


             <div class="col-12  col-md-3  pb-0">
                 <label> Desde: </label>
                 <input class="form-control form-control-sm" type="date" id="FECHA_DESDE" onchange="buscarNotaRemision()">
             </div>

             <div class="col-12   col-md-3   pb-0">
                 <label> Hasta:</label>
                 <input class="form-control form-control-sm" type="date" id="FECHA_HASTA" onchange="buscarNotaRemision()">
             </div>

             <div class="col-12   col-md-3   pb-0">
                 <label> Por Estado:</label>
                 <select class="form-select p-0" id="ESTADO" onchange="buscarNotaRemision()">
                 <option value="P">Pendiente</option>
                 <option value="C">Aceptado</option>
                 </select>
             </div>
         </div>


     </x-search-report-downloader>



     <div class="mt-2" id="grill">
         @include("remision_de_terminados.confirmar.grill")
     </div>
 </div>

 @endsection


 @section("jsScripts")


 <script src="<?= url("assets/xls_gen/xls.js") ?>"></script>
 <script src="<?= url("assets/xls_gen/xls_ini.js?v=" . rand(0.0, 100)) ?>"></script>
 <script src="<?= url("assets/js/buscador.js?v=" . rand(0.0, 100)) ?>"></script>

 <script>
     function configurarBusqueda(url_opcional) {
         if (!("dataSearcher" in window))
             window.dataSearcher = new DataSearcher();
         let grill_url = "<?= url('remision-prod-terminados/index/confirmar') ?>";

         if (url_opcional) {
             url_opcional.preventDefault();
             grill_url = url_opcional.currentTarget.href;
         }
         //PARMETROS
         let F_desde = $("#FECHA_DESDE").val();
         let F_hasta = $("#FECHA_HASTA").val();
         let estado = $("#ESTADO").val();
         let parametros = {};
         if (F_desde != "") parametros.FECHA_DESDE = F_desde;
         if (F_hasta != "") parametros.FECHA_HASTA = F_hasta;
         if (estado != "") parametros.ESTADO = estado;
         dataSearcher.setUrl = grill_url;
         dataSearcher.setOutputTarget = "#grill";
         dataSearcher.setParametros = parametros;
     }

     function buscarNotaRemision(url_opcional) {

         configurarBusqueda(url_opcional)
         dataSearcher.formatoHtml();
     }








     window.onload = function() {

         configurarBusqueda();
         
     }
 </script>


 @endsection
 @extends( "templates.admin.index")

 @section("PageTitle")
 Salidas de Productos & Materia Prima
 @endsection


 @section("content")

 <div class="container-fluid col-12 col-md-12 col-lg-10 col-xl-8 bg-dark pb-5">
     <h2 class="text-center mt-2 text-light">Salidas de productos y materia prima</h2>

     <div id="loaderplace"></div>

     <a class="btn btn-warning mb-1" href="{{url('salida/create')}}">NUEVA SALIDA</a>


     <x-search-report-downloader placeholder="BUSCAR POR DESCRIPCION" callback="buscarSalidas()" showSearcherInput="N">


         <div class="row pt-1 text-light w-100">


             <div class="col-12  col-md-3  pb-0">
                 <label> Desde: </label>
                 <input class="form-control form-control-sm" type="date" id="FECHA_DESDE" onchange="buscarSalidas()">
             </div>

             <div class="col-12   col-md-3   pb-0">
                 <label> Hasta:</label>
                 <input class="form-control form-control-sm" type="date" id="FECHA_HASTA" onchange="buscarSalidas()">
             </div>
         </div>


     </x-search-report-downloader>



     <div class="mt-2" id="grill">

         @include("salida.index.grill")
     </div>
 </div>

 @endsection


 @section("jsScripts")


 <script src="<?= url("assets/xls_gen/xls.js") ?>"></script>
 <script src="<?= url("assets/xls_gen/xls_ini.js?v=" . rand(0.0, 100)) ?>"></script>
 <script src="<?= url("assets/js/buscador.js?v=" . rand(0.0, 100)) ?>"></script>

 <script>


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
        if ("ok" in resp) fill_grill();
        else alert(resp.err);

    }
    

     async function fill_grill(url_optional) {


         let grill_url = ("<?= url('salida/index') ?>");

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
         hide_loader();

         $("#grill").html(resp);



     }







     function prepararBusquedas() {
        //PARMETROS
        
        let F_desde = $("#FECHA_DESDE").val();
        let F_hasta = $("#FECHA_HASTA").val();
     
        let parametros = { };
        if( F_desde  != "")   parametros.FECHA_DESDE=  F_desde;
        if( F_hasta  != "")   parametros.FECHA_HASTA=  F_hasta;


        dataSearcher.setUrl=  "<?=url('salida/index')?>";
        dataSearcher.setOutputTarget = "#grill";
        dataSearcher.setParametros = parametros; 
    }

     
     function buscarSalidas() {
       prepararBusquedas();
        dataSearcher.formatoHtml();
    }



     var dataSearcher = undefined;

     window.onload = function() {
         dataSearcher = new DataSearcher();
         prepararBusquedas();
         // fill_grill();
     }
 </script>


 @endsection
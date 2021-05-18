 @extends( "templates.admin.index")

 @section("PageTitle")
 Salidas de Productos & Materia Prima
 @endsection


 @section("content")

 <div class="container-fluid col-12 col-md-12 col-lg-10 col-xl-8 bg-dark pb-5">
     <h2 class="text-center mt-2 text-light">Notas de residuo</h2>

     <div id="loaderplace"></div>

     <a class="btn btn-warning mb-1" href="{{url('nota-residuos/create')}}">NUEVA NOTA DE RESIDUO</a>


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


         let grill_url = $("<?=url('remision-prod-terminados/index')?>").val();

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

         

     }
 </script>


 @endsection
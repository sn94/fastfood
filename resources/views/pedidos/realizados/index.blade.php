 @extends("templates.caja.index")

 @section("content")



 <div class="container col-12 col-md-12 col-lg-10 fast-food-bg   mt-1 pt-3 pb-lg-5 ">


   @include("validations.formato_numerico")
   @include("validations.form_validate")



   <!--Modal -->
   <x-fast-food-modal id="PEDIDO-MODAL" title="EDITAR PEDIDO" />


   <h3 class="fast-food-big-title">Pedidos de productos, materia prima y otros</h3>

   <x-search-report-downloader placeholder="BUSCAR POR CÓDIGO DE BARRA O DESCRIPCION" callback="fill_grill()">
     <label>Pedidos en la Fecha: <input onchange="fill_grill()" type="date" value="{{date('Y-m-d')}}" id="FECHA">
   </x-search-report-downloader>




   <div class="container-fluid mb-5 pb-5" id="grill">

     @include("pedidos.realizados.grill")
   </div>

 </div>

 <script>
  async function fill_grill(ev) {
     prepararBusqueda(ev);
     dataSearcher.formatoHtml();
   }
   async function prepararBusqueda(ev) {
     if (!("dataSearcher" in window))
       window.dataSearcher = new DataSearcher();
     //configurar objeto
     let buscado = $("#search").val();
     let fecha = $("#FECHA").val();
     let parametros = {
       buscado: buscado
     };
     if(  fecha &&  fecha != "")
     parametros.fecha=  fecha;

     //Determinar link
     let page_index = 1;
     //prevenir propagacion
     if (ev != undefined && typeof ev == "object") {
       ev.preventDefault();
       let url_parts = ev.target.href.split("?");
       if (url_parts.length > 1) page_index = url_parts[1].split("=")[1];
     }

     let urlDeBusqueda = "<?= url('pedidos/realizados') ?>?page=" + page_index;

     dataSearcher.setUrl = urlDeBusqueda;
     dataSearcher.setOutputTarget = "#grill";
     dataSearcher.setParametros = parametros;

   }


   async function mostrar_form(ev) {

     ev.preventDefault();
     let url_ = ev.target.href;
     let req = await fetch(url_);
     let resp = await req.text();
     $("#PEDIDO-MODAL .modal-body").html(resp);
     $("#PEDIDO-MODAL").modal("show");

   }


   async function pedir_recibir(ev) {

     ev.preventDefault();
     let url_ = ev.target.action;

     formValidator.init(ev.target);

     let req = await fetch(url_, {
       method: ev.target.method,
       headers: {
         'Content-Type': "application/x-www-form-urlencoded",
         'X-CSRF-TOKEN': formValidator.getData("application/json")["__token"]
       },
       body: formValidator.getData()
     });
     let resp = await req.json();
     if ("ok" in resp) {
       alert(resp.ok);
       $("#PEDIDO-MODAL .modal-body").html("");
       $("#PEDIDO-MODAL").modal("hide");
       fill_grill();
     } else alert(resp.err);

     //actualizar grill, mostrar estados
   }


   window.onload = function() {
     prepararBusqueda();
     formatoNumerico.formatearCamposNumericosDecimales();
   };
 </script>
 @endsection

 @section("jsScripts")


 <script src="<?= url("assets/xls_gen/xls.js") ?>"></script>
 <script src="<?= url("assets/xls_gen/xls_ini.js?v=" . rand(0.0, 100)) ?>"></script>
 <script src="<?= url("assets/js/buscador.js?v=" . rand(0.0, 100)) ?>"></script>


 @endsection
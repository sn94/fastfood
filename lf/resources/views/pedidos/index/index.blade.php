 @extends("templates.caja.index")

 @section("content")



 <div class="container col-12 col-md-12 col-lg-10 bg-dark text-light mt-1">


   @include("validations.formato_numerico")
   @include("validations.form_validate")



   <!--Modal -->
   <x-fast-food-modal id="PEDIDO-MODAL" title="CONFIRMAR PEDIDO" />



   <div class="row">
     <div class="col-12 col-md-3">
       <table class="mb-2">
         <tr>
           <td style="width: 50px;background: #71d788;height: 20px;"></td>
           <td style="color: white !important;font-weight: 600;">APROBADO</td>
         </tr>
         <tr>
           <td style="width: 50px;background: #f0d88e;height: 20px;"></td>
           <td style="color: white !important;font-weight: 600;">PENDIENTE</td>
         </tr>
       </table>
     </div>

   </div>

   <h4 class="text-center">Pedidos de {{ strtolower( $STOCK->DESCRIPCION)}}</h4>

   <div class="container-fluid" id="grill">

     @include("pedidos.index.grill")
   </div>

 </div>

 <script>
   async function fill_grill() {


     let grill_url = "<?= url('pedidos/list/'.$STOCK->REGNRO) ?>";


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
     formatoNumerico.formatearCamposNumericosDecimales();
   };
 </script>
 @endsection
 @extends("templates.caja.index")

 @section("content")





 @include("validations.formato_numerico")
 @include("validations.form_validate")



 <!--Modal -->
 <x-fast-food-modal id="PEDIDO-MODAL" title="NUEVO PEDIDO" />


 <div class="container col-12 col-md-12 col-lg-10 bg-dark text-light mt-1 pb-5">

   <h3 class="text-center">Pedidos </h3>


   <div class="container-fluid" id="grill">

     @include("pedidos.vendidos.grill")
   </div>

 </div>

 <script>
   async function fill_grill(ev) {
     let page_index = 1;

     //prevenir propagacion
     if (ev != undefined && typeof ev == "object") {
       ev.preventDefault();
       let url_parts = ev.target.href.split("?");
       if (url_parts.length > 1) page_index = url_parts[1].split("=")[1];
     }

     let grill_url = "<?= url('pedidos') ?>?page="+page_index;
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
     if (parseInt(req.status) != 200) {
       $("#PEDIDO-MODAL .modal-body").html("");
       $("#PEDIDO-MODAL").modal("hide");
       alert(req.statusText);
       return;
     }


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
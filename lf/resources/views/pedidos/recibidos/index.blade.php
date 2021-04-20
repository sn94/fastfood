 @extends("templates.admin.index")

 @section("content")





 @include("validations.formato_numerico")
 @include("validations.form_validate")
 <!--Modal -->
 <x-fast-food-modal id="APROBACION-SALIDA-MODAL"  title="APROBACIÓN DE PEDIDO" callback="aprobar()"/>

 <div class="container-fluid col-12 col-md-12 col-lg-8  bg-dark  text-light pb-2 mt-1">
 
 <h3 class="text-center">Pedidos Recibidos</h3>
   <div class="container-fluid" id="grill">

     @include("pedidos.recibidos.grill")
   </div>
 </div>


 <script>
   async function fill_grill() {


     let grill_url = "<?= url('pedidos/recibidos') ?>";
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


async function mostrarFormAprobacion( ev){
  ev.preventDefault();
  let url= ev.target.href;
  let req = await fetch( url);
  let resp=  await  req.text();
  $("#APROBACION-SALIDA-MODAL .modal-body").html(  resp );
  $("#APROBACION-SALIDA-MODAL").modal( "show");

}
 

/*
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
       fill_grill();
     } else alert(resp.err);

     //actualizar grill, mostrar estados
   }

*/ 

   window.onload = function() {

     formatoNumerico.formatearCamposNumericosDecimales();
   };
 </script>
 @endsection
 @php
 use App\Helpers\Utilidades;



 $REGNRO= isset( $parametros )? $parametros->REGNRO : "";
 $CLIENTE_PORDEFECTO= isset( $parametros )? $parametros->CLIENTE_PORDEFECTO : "";
 $MENSAJE_TICKET= isset( $parametros )? $parametros->MENSAJE_TICKET : "";
 $DESCONTAR_MP_EN_VENTA=    isset( $parametros )? $parametros->DESCONTAR_MP_EN_VENTA : "N";
 $EMAIL_ADMIN= isset( $parametros )? $parametros->EMAIL_ADMIN : "";

 @endphp



 <input type="hidden" id="parametros-URL" value="{{url('parametros')}}">
 @if( $REGNRO != "")
 <input type="hidden" name="REGNRO" value="{{$REGNRO}}">
 @endif

 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

 <div class="row  pt-2 ">
     <div class="col-12" >
         <label  >Mensaje para ticket: </label>
         <input style="height: 30px !important; width: 100%;" name="MENSAJE_TICKET" class="form-control" type="text" maxlength="30" value="{{$MENSAJE_TICKET}}" />
     </div>


     <div class="col-12"  >
          
         <x-pretty-checkbox name="DESCONTAR_MP_EN_VENTA" :value="$DESCONTAR_MP_EN_VENTA" label="Descontar materia prima en la venta" onValue="S" offValue="N"   />

     </div>



     <div class="col-12  " >
         <label  >Email administrativo: </label>
         <input style="color:black !important;height: 30px !important; width: 100%;" name="EMAIL_ADMIN" class="form-control" type="text" maxlength="100" value="{{$EMAIL_ADMIN}}" />
     </div>



     <div class="col-12   d-flex justify-content-center pt-1">
         <button style="height: 30px !important;" type="submit" class="btn fast-food-form-button ">Guardar</button>
     </div>


 </div>

 @include("validations.form_validate")
 @include("validations.formato_numerico")
 <script>
     async function guardar(ev) {
         //config_.processData= false; config_.contentType= false;
         ev.preventDefault();
         show_loader();
         formValidator.init(ev.target);
         let req = await fetch(ev.target.action, {
             "method": "POST",
             headers: {
                 "Content-Type": "application/x-www-form-urlencoded",
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

             },
             body: formValidator.getData()
         });
         let resp = await req.json();
         hide_loader();
         if ("ok" in resp) {
             alert(resp.ok);

         } else {

             alert(resp.err);
         }


     }
 </script>
 @php
 use App\Helpers\Utilidades;



 $REGNRO= isset( $parametros )? $parametros->REGNRO : "";
 $CLIENTE_PORDEFECTO= isset( $parametros )? $parametros->CLIENTE_PORDEFECTO : "";
 $MENSAJE_TICKET= isset( $parametros )? $parametros->MENSAJE_TICKET : "";

 @endphp



 <input type="hidden" id="parametros-URL" value="{{url('parametros')}}">
 @if( $REGNRO != "")
 <input type="hidden" name="REGNRO" value="{{$REGNRO}}">
 @endif

 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

 <div class="row bg-dark   pt-2 ">
     <div class="col-12  " style="display: flex;flex-direction: row;">
         <label style="color: white !important;width: 100%;" class="pr-1">MENSAJE PARA TICKET: </label>
         <input style="color:black !important;height: 30px !important; width: 100%;background-color: white !important;" name="MENSAJE_TICKET" class="form-control" type="text" maxlength="30" value="{{$MENSAJE_TICKET}}" />
     </div>


     <div class="col-12   d-flex justify-content-center ">
         <button style="height: 30px !important;" type="submit" class="btn btn-warning ">GUARDAR</button>
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
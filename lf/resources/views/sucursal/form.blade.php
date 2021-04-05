 @php
 use App\Helpers\Utilidades;



 $REGNRO= isset( $sucursal )? $sucursal->REGNRO : "";
 $DESCRIPCION= isset( $sucursal )? $sucursal->DESCRIPCION : "";
 $MATRIZ= isset( $sucursal )? $sucursal->MATRIZ : "N";
 $ORDEN= isset( $sucursal )? $sucursal->ORDEN : "0";
 @endphp



 <input type="hidden" id="SUCURSAL-URL" value="{{url('sucursales')}}">
 <div class="row">
     @if( $REGNRO != "")
     <input type="hidden" name="REGNRO" value="{{$REGNRO}}">
     @endif

     <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">



     <div class="col-12 col-md-3">

         <x-pretty-checkbox name="MATRIZ" :value="$MATRIZ" onValue="S" offValue="N" label="MATRIZ" />
     </div>

     <div class="col-12 col-md-5">

         <label class="pr-2">DESCRIPCIÓN: </label>
         <input name="DESCRIPCION" class="form-control" type="text" maxlength="80" value="{{$DESCRIPCION}}" />

     </div>
     <div class="col-12 col-md-2 ">

         <label class="pr-2">ORDEN: </label>
         <input name="ORDEN" class="form-control" type="text" maxlength="2" value="{{$ORDEN}}" />

     </div>

     <div class="col-12  col-md-2 d-flex align-items-end">

         <button type="submit" class="btn btn-warning btn-sm">GUARDAR</button>
     </div>
 </div>
 @include("validations.form_validate")
 @include("validations.formato_numerico")
 <script>
     async function guardar(ev) {
         //config_.processData= false; config_.contentType= false;


         ev.preventDefault();
         formValidator.init(ev.target);
         show_loader();
         let req = await fetch(ev.target.action, {
             "method": "POST",

             headers: {

                 'Content-Type': "application/x-www-form-urlencoded",
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

             },
             body: formValidator.getData()
         });
         let resp = await req.json();
         hide_loader();
         if ("ok" in resp) {
             alert(resp.ok);
             formValidator.limpiarCampos();
             fill_grill();

             //window.location.reload();
         } else {

             alert(resp.err);
         }


     }
 </script>
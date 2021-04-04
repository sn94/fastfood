 @php
 use App\Helpers\Utilidades;



 $REGNRO= isset( $medidas )? $medidas->REGNRO : "";
 $DESCRIPCION= isset( $medidas )? $medidas->DESCRIPCION : "";
 @endphp



 <input type="hidden" id="medidas-URL" value="{{url('medidas')}}">
 @if( $REGNRO != "")
 <input type="hidden" name="REGNRO" value="{{$REGNRO}}">
 @endif

 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

 <div class="row bg-dark   pt-2 ">
     <div class="col-12 col-md-8 " style="display: flex;flex-direction: row;">
         <label style="color: white !important;"  class="pr-1">DESCRIPCIÓN: </label>
         <input style="color:black !important;height: 30px !important; width: 100%;background-color: white !important;" name="DESCRIPCION" class="form-control" type="text" maxlength="50" value="{{$DESCRIPCION}}" />
     </div>


     <div class="col-12  col-md-4  d-flex justify-content-center ">
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


                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

             },
             body: new FormData(ev.target)
         });
         let resp = await req.json();
         hide_loader();
         if ("ok" in resp) {
             alert(resp.ok);

             formValidator.limpiarCampos();
             fill_grill();
         } else {

             alert(resp.err);
         }


     }
 </script>
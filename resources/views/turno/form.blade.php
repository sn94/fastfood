 @php
 use App\Helpers\Utilidades;



 $REGNRO= isset( $turno )? $turno->REGNRO : "";
 $DESCRIPCION= isset( $turno )? $turno->DESCRIPCION : "";
 $ORDEN= isset( $turno )? $turno->ORDEN : "";
 @endphp



 <input type="hidden" id="CARGO-URL" value="{{url('turno')}}">
 @if( $REGNRO != "")
 <input type="hidden" name="REGNRO" value="{{$REGNRO}}">
 @endif

 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

 <div class="row  pt-2 ">
     <div class="col-12 col-md-4 "> 
         <label   class="pr-1">Descripción: </label>
         <input  name="DESCRIPCION" class="form-control" type="text" maxlength="50" value="{{$DESCRIPCION}}" />
     </div>
     <div class="col-12 col-md-2">
         <label   class="pr-1">ORDEN: </label>
         <input  name="ORDEN" class="form-control" type="text" maxlength="2" value="{{$ORDEN}}" />
     </div>


     <div class="col-12  col-md-2  d-flex align-items-end ">
          <button type="submit" class="btn fast-food-form-button">Guardar</button>
     </div>


 </div>

 @include("validations.form_validate")
 @include("validations.formato_numerico")
 <script>
     async function guardar(ev) {
         //config_.processData= false; config_.contentType= false;
         ev.preventDefault();
         if ("show_loader" in window)
             show_loader();
         formValidator.init(ev.target);
         let req = await fetch(ev.target.action, {
             "method":   $("input[name=_method]").val(),
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                 'Content-Type': 'application/x-www-form-urlencoded'

             },
             body: formValidator.getData()
         });
         let resp = await req.json();
         if ("hide_loader" in window)
             hide_loader();
         if ("ok" in resp) {
             alert(resp.ok);

             formValidator.limpiarCampos();
             if( "fill_grill" in window)
             fill_grill();
             if( "cerrarMyModal" in window)
             cerrarMyModal();
         } else {

             alert(resp.err);
         }


     }
 </script>
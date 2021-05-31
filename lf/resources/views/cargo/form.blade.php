 @php
 use App\Helpers\Utilidades;



 $REGNRO= isset( $cargo )? $cargo->REGNRO : "";
 $DESCRIPCION= isset( $cargo )? $cargo->DESCRIPCION : "";
 $ORDEN= isset( $cargo )? $cargo->ORDEN : "";
 @endphp



 <input type="hidden" id="CARGO-URL" value="{{url('cargo')}}">
 @if( $REGNRO != "")
 <input type="hidden" name="REGNRO" value="{{$REGNRO}}">
 @endif

 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

 <div class="row  pt-2 ">
     <div class="col-12 col-md-5 ">
         <label class="pr-1">Descripción: </label>
         <input name="DESCRIPCION" class="form-control" type="text" maxlength="50" value="{{$DESCRIPCION}}" />
     </div>

     <div class="col-12 col-md-2 ">
         <label class="pr-1">ORDEN: </label>
         <input name="ORDEN" class="form-control" type="text" maxlength="2" value="{{$ORDEN}}" />
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
         show_loader();
         formValidator.init(ev.target);
         let req = await fetch(ev.target.action, {
             "method": $("input[name=_method]").val(),

             headers: {
                 'Content-Type': 'application/x-www-form-urlencoded',
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
             let formRefrescado = await fetch("<?=url('cargo/create')?>");
             let formHtml = await formRefrescado.text();
             $("#form").html(formHtml);
         } else {

             alert(resp.err);
         }


     }
 </script>
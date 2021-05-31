 @php
 use App\Helpers\Utilidades;



 $REGNRO= isset( $familia )? $familia->REGNRO : "";
 $DESCRIPCION= isset( $familia )? $familia->DESCRIPCION : "";
 @endphp



 <input type="hidden" id="FAMILIA-URL" value="{{url('familia')}}">
 @if( $REGNRO != "")
 <input type="hidden" name="REGNRO" value="{{$REGNRO}}">
 @endif

 <input type="hidden" name="_token" value="{{csrf_token()}}">

 <div class="row    pt-2 ">
     <div class="col-12 col-md-8 " style="display: flex;flex-direction: row;">
         <label   class="pr-1">Descripción: </label>
         <input style="height: 30px !important; width: 100%;" name="DESCRIPCION" class="form-control" type="text" maxlength="50" value="{{$DESCRIPCION}}" />
     </div>



     <div class="col-12  col-md-4  d-flex justify-content-center   ">
        <x-pretty-checkbox name="MOSTRAR_EN_VENTA"  value="S" label="Mostrar en venta"  onValue="S" offValue="N"  />
     </div>
     <div class="col-12 ">
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
             $("#form").addClass("d-none");
             $("#BOTOB-NUEVO").removeClass("d-none");
             await refrescarPanelPosiciones();
             fill_grill();
         } else {

             alert(resp.err);
         }


     }
 </script>
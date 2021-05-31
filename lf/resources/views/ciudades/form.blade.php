 @php
 use App\Helpers\Utilidades;
 use App\Models\Ciudades_departa;



 $REGNRO= isset( $ciudades )? $ciudades->regnro : "";
 $DEPARTA= isset( $ciudades )? $ciudades->departa : "";
 $DESCRIPCION= isset( $ciudades )? $ciudades->ciudad : "";


 $departamentos= Ciudades_departa::get();
 
 @endphp



 <input type="hidden" id="ciudades-URL" value="{{url('ciudades')}}">
 @if( $REGNRO != "")
 <input type="hidden" name="REGNRO" value="{{$REGNRO}}">
 @endif

 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

 <div class="row  p-2 ">
     <div class="col-12 col-md-6 " style="display: flex;flex-direction: row;">
         <label    class="pr-1">DEPART.: </label>
         <select style="font-size: 12.5px ;height: 30px !important; width: 100%;" name="departa" class="form-control"   >
         @foreach( $departamentos as $depa)
         @if(  $DEPARTA ==  $depa->regnro)
         <option selected value="{{$depa->regnro}}">{{$depa->departa}}</option>
         @else
         <option value="{{$depa->regnro}}">{{$depa->departa}}</option>
         @endif
         @endforeach
         
         </select>
     </div>
     <div class="col-12 col-md-6 " style="display: flex;flex-direction: row;">
         <label    class="pr-1">NOMBRE: </label>
         <input style="font-size: 12.5px ; height: 30px !important; width: 100%;" name="ciudad" class="form-control" type="text" maxlength="50" value="{{$DESCRIPCION}}" />
     </div>


     <div class="col-12  col-md-12  d-flex justify-content-center ">
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


                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

             },
             body: new FormData(ev.target)
         });
         let resp = await req.json();
         hide_loader();
         if ("ok" in resp) {
           //  alert(resp.ok);

             formValidator.limpiarCampos();
             fill_grill();
         } else {

             alert(resp.err);
         }


     }
 </script>
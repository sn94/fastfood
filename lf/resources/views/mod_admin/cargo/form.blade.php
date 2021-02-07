 @php
 use App\Helpers\Utilidades;



 $REGNRO= isset( $cargo )?  $cargo->REGNRO : ""; 
 $DESCRIPCION= isset( $cargo )?  $cargo->DESCRIPCION : ""; 
 @endphp


 <style>
     .form-control {
         background: white !important;
         color: black !important;
         height: 40px !important;
     }

     label {
         font-size: 18px !important;
         color: white;
     }
 </style>



 <input type="hidden" id="CARGO-URL" value="{{url('cargo')}}">
 <div class="row">

     <div class="col-12 offset-md-2 col-md-8">
         <div class="row bg-dark pb-2 pr-2 pl-2 pr-md-2 pl-md-2">
             <div class="col-12 offset-md-4 col-md-4">


                 @if( $REGNRO != "")
                 <input type="hidden" name="REGNRO" value="{{$REGNRO}}">
                 @endif

                 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                 <label>DESCRIPCIÓN </label>
                 <input name="DESCRIPCION" class="form-control" type="text" maxlength="50" value="{{$DESCRIPCION}}" />
 
             </div> 


             <div class="col-12 offset-md-4 col-md-4 mt-2 d-flex justify-content-center">
                 <button type="submit" class="btn btn-warning btn-lg">GUARDAR</button>
             </div>


         </div>
     </div>
 </div>
 
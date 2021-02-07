 @php
 use App\Helpers\Utilidades;



 $REGNRO= isset( $sucursal )?  $sucursal->REGNRO : ""; 
 $DESCRIPCION= isset( $sucursal )?  $sucursal->DESCRIPCION : ""; 
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



 <input type="hidden" id="SUCURSAL-URL" value="{{url('sucursales')}}">
 <div class="row">

     <div class="col-12 offset-md-2 col-md-8">
         <div class="row bg-dark pb-2 pr-2 pl-2 pr-md-2 pl-md-2">
             <div class="col-12 offset-md-4 col-md-4">


                 @if( $REGNRO != "")
                 <input type="hidden" name="REGNRO" value="{{$REGNRO}}">
                 @endif

                 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                 <label>DESCRIPCIÓN </label>
                 <input name="DESCRIPCION" class="form-control" type="text" maxlength="80" value="{{$DESCRIPCION}}" />
 
             </div> 


             <div class="col-12 offset-md-4 col-md-4 mt-2 d-flex justify-content-center">
                 <button type="submit" class="btn btn-warning btn-lg">GUARDAR</button>
             </div>


         </div>
     </div>
 </div>
 
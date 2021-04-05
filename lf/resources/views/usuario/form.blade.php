 @php
 use App\Helpers\Utilidades;
 use App\Models\Sucursal;
 use App\Models\Cargo;
 use App\Models\Turno;
 use App\Models\Niveles;

 //FUENTES
 $SUCURSALES= Sucursal::get();
 $CARGOS= Cargo::get();
 $TURNOS= Turno::get();
 $NIVELES= ["SUPER" => "SUPERVISOR", "CAJA" => "CAJA"];  //Niveles::get();

 $REGNRO= isset( $usuario )? $usuario->REGNRO : "";
 $USUARIO= isset( $usuario )? $usuario->USUARIO : "";
 $NOMBRES=isset( $usuario )? $usuario->NOMBRES : "";
 $CEDULA=isset( $usuario )? $usuario->CEDULA : "";
 $SUCURSAL=isset( $usuario )? $usuario->SUCURSAL : "";
 $CARGO=isset( $usuario )? $usuario->CARGO : "";
 $TURNO=isset( $usuario )? $usuario->TURNO : "";
 $CELULAR=isset( $usuario )? $usuario->CELULAR : "";
 $EMAIL=isset( $usuario )? $usuario->EMAIL : "";
 $NIVEL=isset( $usuario )? $usuario->NIVEL : "";
 $ORDEN=isset( $usuario )? $usuario->ORDEN : "";
 @endphp

 


@include("validations.formato_numerico")

 <input type="hidden" id="USUARIO-URL" value="{{url('usuario')}}">
 @if( $REGNRO != "")
 <input type="hidden" name="REGNRO" value="{{$REGNRO}}">
 @endif

 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">


 <div class="row">

     <div class="col-12 offset-md-2 col-md-8">
         <div class="row bg-dark pb-2 pr-2 pl-2 pr-md-2 pl-md-2">

             <div class="col-12  col-md-4">
                 <label>USUARIO(*): </label>
                 <input required name="USUARIO" class="form-control" type="text" maxlength="20" value="{{$USUARIO}}" />


                 <label>NIVEL: </label>

                 <select class="form-control" name="NIVEL">
                     @foreach( $NIVELES as $nivel=>$niveldes)
                     @if( $nivel == $NIVEL )
                     <option selected value="{{$nivel}}">{{$niveldes}}</option>
                     @else
                     <option value="{{$nivel}}">{{$niveldes}}</option>
                     @endif
                     @endforeach
                 </select>


                 <label>SUCURSAL: </label>
                 <select class="form-control" name="SUCURSAL">
                     @foreach( $SUCURSALES as $sucursal)
                     @if( $sucursal->REGNRO == $SUCURSAL )
                     <option selected value="{{$sucursal->REGNRO}}">{{$sucursal->DESCRIPCION}}</option>
                     @else
                     <option value="{{$sucursal->REGNRO}}">{{$sucursal->DESCRIPCION}}</option>
                     @endif
                     @endforeach
                 </select>
             </div>

             <div class="col-12   col-md-4">


                 <label>CARGO: </label>
                 <select class="form-control" name="CARGO">
                     @foreach( $CARGOS as $cargo)
                     @if( $cargo->REGNRO == $CARGO )
                     <option selected value="{{$cargo->REGNRO}}">{{$cargo->DESCRIPCION}}</option>
                     @else
                     <option value="{{$cargo->REGNRO}}">{{$cargo->DESCRIPCION}}</option>
                     @endif
                     @endforeach
                 </select>

                 <label>CÉDULA(*): </label>
                 <input required name="CEDULA" class="form-control" type="text" maxlength="8" value="{{$CEDULA}}" />
                 <label>NOMBRES(*): </label>
                 <input required name="NOMBRES" class="form-control" type="text" maxlength="30" value="{{$NOMBRES}}" />
             </div>

             <div class="col-12 col-md-4">

                 <label>TURNO: </label>

                 <select class="form-control" name="TURNO">
                     @foreach( $TURNOS as $turno)
                     @if( $turno->REGNRO == $TURNO )
                     <option selected value="{{$turno->REGNRO}}">{{$turno->DESCRIPCION}}</option>
                     @else
                     <option value="{{$turno->REGNRO}}">{{$turno->DESCRIPCION}}</option>
                     @endif
                     @endforeach
                 </select>


                 <label>CELULAR: </label>
                 <input name="CELULAR" class="form-control" type="text" maxlength="20" value="{{$CELULAR}}" />
                 <label>EMAIL(*): </label>
                 <input required name="EMAIL" class="form-control" type="text" maxlength="100" value="{{$EMAIL}}" />
             </div>

             <div class="col-12 col-md-4  ">

                 <label> Cambiar contraseña <input onchange="$('#MYPASS').prop('disabled', !this.checked); " type="checkbox"> </label>
                 <input disabled name="PASS" class="form-control" type="text" id="MYPASS" />
             </div>
             <div class="col-12 col-md-4  ">
             <label>ORDEN: </label>
                 <input name="ORDEN" class="form-control entero" type="text" maxlength="2" value="{{$ORDEN}}" />
             </div>

             <div class="col-12 offset-md-4 col-md-4 mt-2 d-flex justify-content-center">
                 <button type="submit" class="btn btn-warning btn-sm">GUARDAR</button>
             </div>


         </div>
     </div>
 </div>
 @include("validations.form_validate")
 @include("validations.formato_numerico")


 <script>
     function show_loader() {
         let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
         $("#loaderplace").html(loader);
     }

     function hide_loader() {
         $("#loaderplace").html("");
     }






     async function guardar(ev) {
         //config_.processData= false; config_.contentType= false;

         ev.preventDefault();

         show_loader();

         let req = await fetch(ev.target.action, {
             "method":  $("input[name=_method]").val() ,
             headers: {
                 'Content-Type': 'application/x-www-form-urlencoded',
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

             },
             body: $(ev.target).serialize()
         });
         let resp = await req.json();
         hide_loader();
         if ("ok" in resp) {
             alert(resp.ok);
             //formValidator.limpiarCampos(ev.target);

             window.location= "<?=url("usuario")?>";
         } else {

             alert(resp.err);
         }


     }



     window.onload= function(){
         formatoNumerico.formatearCamposNumericosDecimales();
     }
 </script>
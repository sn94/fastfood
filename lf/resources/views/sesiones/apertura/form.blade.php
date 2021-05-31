 <?php

    use App\Helpers\Utilidades;
    use App\Models\Caja;
    use App\Models\Sesiones;
    use App\Models\Turno;
    use App\Models\Usuario;

    $TURNOS =  Turno::get();
    $CAJAS =  Caja::get();



    //Datos del form
    $SUCURSAL = isset($SESION) ? $SESION->SUCURSAL : session("SUCURSAL");
    $TURNO =  isset($SESION) ? $SESION->TURNO : "";
    $CAJA =  isset($SESION) ? $SESION->CAJA : "";
    /**Sesion id */
    $REGNRO =   isset($SESION) ? $SESION->REGNRO : (new Sesiones())->PROXIMO_ID();
    $FECHA_APE =   isset($SESION) ? $SESION->FECHA_APE->format('Y-m-d') :  date("Y-m-d");
    $HORA_APE = isset($SESION) ? $SESION->HORA_APE :  date("H:i");
    $CAJERO =   isset($SESION) ? $SESION->CAJERO :  session("ID");
    $CAJERO_NOM =   isset($SESION) ? $SESION->cajero->NOMBRES : (Usuario::find(session("ID"))->NOMBRES);
    $EFECTIVO_INI = isset($SESION) ? Utilidades::number_f($SESION->EFECTIVO_INI) :  0;


    ?>

 <div id="loaderplace">

 </div>
 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

 <input type="hidden" name="SUCURSAL" value="{{$SUCURSAL}}">

 <div class="container col-12 col-md-12 "  >
     <h3 class="fast-food-big-title">Sesión N° {{$REGNRO}}</h3>
 </div>

 <div class="container-fluid ">

     <label class="fs-6">Fecha de apertura: </label>
     <input readonly value="{{$FECHA_APE}}" name="FECHA_APE" class="form-control fs-6 p-1" type="date" />

     <label class="fs-6">Hora de apertura: </label>
     <input readonly value="{{$HORA_APE}}" name="HORA_APE" class="form-control fs-6 p-1" type="text" />

     <label class="fs-6">Turno: </label>

     <select class="form-control fs-6" name="TURNO" {{ isset($SESION)? 'disabled' : '' }}>
         @foreach( $TURNOS as $caja)
         @if( $TURNO == $caja->REGNRO)
         <option selected value="{{$caja->REGNRO}}"> {{$caja->DESCRIPCION}} </option>
         @else
         <option value="{{$caja->REGNRO}}"> {{$caja->DESCRIPCION}} </option>
         @endif
         @endforeach
     </select>
     <label class="fs-6">Caja N°: </label>
     <select class="form-control fs-6" name="CAJA" {{ isset($SESION)? 'disabled' : '' }}>
         @foreach( $CAJAS as $caja)
         @if( $CAJA == $caja->REGNRO)
         <option selected value="{{$caja->REGNRO}}"> {{$caja->DESCRIPCION}} </option>
         @else
         <option value="{{$caja->REGNRO}}"> {{$caja->DESCRIPCION}} </option>
         @endif

         @endforeach
     </select>


     <label class="fs-6">Cajero: </label>
     <div style="display: flex;">
         <input style="width: 80px !important;" readonly name="CAJERO" class="form-control fs-6 p-1" type="text" value="{{$CAJERO}}" />
         <input readonly class="form-control fs-6 p-1" type="text" value="{{$CAJERO_NOM}}" />
     </div>

     <label class="fs-6">Efectivo inicial: </label>
     <input <?= isset($SESION) ? 'disabled' : '' ?> value="{{$EFECTIVO_INI}}" onblur="if(this.value=='')this.value='0';" onfocus="if(this.value=='0')this.value='';" name="EFECTIVO_INI" class="form-control entero fs-6 p-1" type="text" />

 </div>



 <div class="col-12 d-flex justify-content-center">
     @if( isset($SESION) )
     <a class="btn btn-danger mt-2 " href="<?= url('sesiones/cerrar') ?>">CERRAR</a>
     <a class="btn btn-success mt-2 " href="<?= url('modulo-caja') ?>">CONTINUAR</a>
     @else
     <button type="submit" class="btn fast-food-form-button">ABRIR</button>
     @endif


 </div>

 @include("validations.form_validate")
 @include("validations.formato_numerico")
 <script>
     async function nuevoTurno() {
         let url_ = "<?= url('turno/create') ?>";
         let req = await fetch(url_, {
             headers: {
                 'X-Requested-With': 'XMLHttpRequest'
             }
         });
         let resp = await req.text();

         $("#mymodal .content").html(resp);
         $("#mymodal").removeClass("d-none");
     }


     function buscarTurno() {

         buscadorGenerico.init(({
             REGNRO,
             DESCRIPCION
         }) => {

             $(".CAJAID").val(REGNRO);
             $(".CAJADESC").val(DESCRIPCION);
         }, 'TURNO');
     }


     async function cerrarSesion(ev) {
         //config_.processData= false; config_.contentType= false;
         ev.preventDefault();
         show_loader();
         let req = await fetch(ev.target.action);
         let resp = await req.json();
         hide_loader();
         if ("ok" in resp) {
             alert(resp.ok);
             window.location = "<?= url("modulo-caja") ?>";
             // formValidator.limpiarCampos();
             //  fill_grill();
         } else {
             alert(resp.err);
         }
     }

     async function guardar(ev) {
         //config_.processData= false; config_.contentType= false;
         ev.preventDefault();
         show_loader();
         formValidator.init(ev.target);
         let req = await fetch(ev.target.action, {
             "method": "POST",
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
             window.location = "<?= url("modulo-caja") ?>";
             // formValidator.limpiarCampos();
             //  fill_grill();
         } else {
             alert(resp.err);
         }
     }







     window.onload = function() {

         formatoNumerico.formatearCamposNumericosDecimales("SESION-FORM");
     }
 </script>
@php 


$REGNRO=  isset(  $cliente )?   $cliente->REGNRO  :  "";
$CEDULA_RUC=  isset(  $cliente )?   $cliente->CEDULA_RUC  :  "";
$NOMBRE=  isset(  $cliente )?   $cliente->NOMBRE  :  "";
$DIRECCION=  isset(  $cliente )?   $cliente->DIRECCION  :  "";
$CIUDAD=  isset(  $cliente )?   $cliente->CIUDAD  :  "";
$TELEFONO=  isset(  $cliente )?   $cliente->TELEFONO  :  "";
$CELULAR=  isset(  $cliente )?   $cliente->CELULAR  :  "";
$EMAIL=  isset(  $cliente )?   $cliente->EMAIL  :  "";
$WEB=  isset(  $cliente )?   $cliente->WEB  :  "";
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


<div class="row">

    <div class="col-12 offset-md-2 col-md-8">
        <div class="row bg-dark pb-2 pr-2 pl-2 pr-md-2 pl-md-2">
            <div class="col-12 col-md-6">


                @if(  $REGNRO !=  "")
                <input type="hidden" name="REGNRO"  value="{{$REGNRO}}">
                @endif 

                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">


                <label for="element_1">RUC/CÉDULA </label>
                <input   name="CEDULA_RUC" class="form-control" type="text" maxlength="15" value="{{$CEDULA_RUC}}" />

                <label for="element_2">RAZÓN SOCIAL </label>
                <input name="NOMBRE" class="form-control" type="text" maxlength="80" value="{{$NOMBRE}}" />

                <label for="element_3">DOMICILIO </label>
                <input name="DIRECCION" class="form-control" type="text" maxlength="80" value="{{$DIRECCION}}" />

                <label for="element_4">CIUDAD </label>
                <input name="CIUDAD" class="form-control" type="text" maxlength="80" value="{{$CIUDAD}}" />
            </div>
            <div class="col-12 col-md-6">
                <label for="element_5">TELÉFONO </label>
                <input oninput="phone_input(event)" name="TELEFONO" class="form-control" type="text" maxlength="20" value="{{$TELEFONO}}" />
                <label for="element_6">CELULAR </label>
                <input oninput="phone_input(event)" name="CELULAR" class="form-control" type="text" maxlength="20" value="{{$CELULAR}}" />

                <label for="element_7">EMAIL </label>
                <input name="EMAIL" class="form-control" type="text" maxlength="80" value="{{$EMAIL}}" />

                <label for="element_7">WEB </label>
                <input name="WEB" class="form-control" type="text" maxlength="80" value="{{$WEB}}" />
            </div>


            <div class="col-12 mt-2 d-flex justify-content-center">
                <button type="submit" class="btn btn-warning btn-lg">GUARDAR</button>
            </div>


        </div>
    </div>
</div>



<script>
    function phone_input(ev) {
        if (ev.data == null) return;

        if ((ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57) && ev.data.charCodeAt() != 32) {
            ev.target.value =
                ev.target.value.substr(0, ev.target.selectionStart - 1) + " "
            ev.target.value.substr(ev.target.selectionStart);
        }
    }



    function solo_numeros(ev) {
        //0 48   9 57
        if (ev.data == null) return;
        if ((ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57)) {
            let cad = ev.target.value;
            let cad_n = cad.substr(0, ev.target.selectionStart - 1) + cad.substr(ev.target.selectionStart + 1);
            ev.target.value = cad_n;
        }
    }





    function limpiar_numero(val) {
        return val.replaceAll(new RegExp(/[.]*/g), "");
    }


    function dar_formato_millares(val_float) {
        return new Intl.NumberFormat("de-DE").format(val_float);
    }

    function formatear_decimal(ev) { //

        if (ev.data == undefined) {
            ev.target.value = "0";
            return;
        }
        if (ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57) {
            let noEsComa = ev.data.charCodeAt() != 44;
            let yaHayComa = ev.data.charCodeAt() == 44 && /(,){1}/.test(ev.target.value.substr(0, ev.target.value.length - 2));
            let comaPrimerLugar = ev.data.charCodeAt() == 44 && ev.target.value.length == 1;
            let comaDespuesDePunto = ev.data.charCodeAt() == 44 && /\.{1},{1}/.test(ev.target.value);
            if (noEsComa || (yaHayComa || comaPrimerLugar || comaDespuesDePunto)) {
                ev.target.value = ev.target.value.substr(0, ev.target.selectionStart - 1) + ev.target.value.substr(ev.target.selectionStart);
                return;
            } else return;
        }
        //convertir a decimal
        //dejar solo la coma decimal pero como punto 
        let solo_decimal = limpiar_numero_para_float(ev.target.value);
        let noEsComaOpunto = ev.data.charCodeAt() != 44 && ev.data.charCodeAt() != 46;
        if (noEsComaOpunto) {
            let float__ = parseFloat(solo_decimal);

            //Formato de millares 
            let enpuntos = dar_formato_millares(float__);
            $(ev.target).val(enpuntos);
        }
    }


    function formatear_entero(ev) {

        //       if (ev.data == undefined) return;
        if (ev.data == null || ev.data == undefined)
            ev.target.value = ev.target.value.replaceAll(new RegExp(/[.]*[,]*/g), "");
        if (ev.data != null && (ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57)) {

            ev.target.value =
                ev.target.value.substr(0, ev.target.selectionStart - 1) +
                ev.target.value.substr(ev.target.selectionStart);
        }
        //Formato de millares
        let val_Act = ev.target.value;
        val_Act = val_Act.replaceAll(new RegExp(/[.]*[,]*/g), "");
        let enpuntos = new Intl.NumberFormat("de-DE").format(val_Act);

        try {
            if (parseInt(enpuntos) == 0) $(ev.target).val("");
            else $(ev.target).val(enpuntos);
        } catch (err) {
            $(ev.target).val(enpuntos);
        }

    }











    /**
    ***
    Envio de formulario
    */  




    function campos_vacios() {
        if ($("input[name=estado]").prop("checked")) return false;

        if (($("input[name=importe1]").val() == "" || $("input[name=importe1]").val() == "0") &&
            ($("input[name=importe2]").val() == "" || $("input[name=importe2]").val() == "0") &&
            ($("input[name=importe3]").val() == "" || $("input[name=importe3]").val() == "0")) {
            alert("Indique al menos de estos importes: 10% | 5% | Exenta");
            return true;
        }

        /*  if ($("input[name=factura]").val() == "") {
              alert("Indique el número de factura");
              return true;
          }*/
        if ($("select[name=moneda]").val() != "1" && $("input[name=tcambio]").val() == "") {
            alert("Indique el tipo de cambio");
            return true;
        }
        return false;

    }

    function show_loader() {
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
    }

    function hide_loader() {
        $("#loaderplace").html("");
    }


 
</script>
@php


$REGNRO= isset( $proveedores )? $proveedores->REGNRO : "";
$CEDULA_RUC= isset( $proveedores )? $proveedores->CEDULA_RUC : "";
$NOMBRE= isset( $proveedores )? $proveedores->NOMBRE : "";
$DIRECCION= isset( $proveedores )? $proveedores->DIRECCION : "";
$CIUDAD= isset( $proveedores )? $proveedores->CIUDAD : "";
$TELEFONO= isset( $proveedores )? $proveedores->TELEFONO : "";
$CELULAR= isset( $proveedores )? $proveedores->CELULAR : "";
$EMAIL= isset( $proveedores )? $proveedores->EMAIL : "";
$WEB= isset( $proveedores )? $proveedores->WEB : "";

 

@endphp

 


<div class="row">
 
        <div class="row bg-dark pb-2 pr-2 pl-2 pr-md-2 pl-md-2">
            <div class="col-12 col-md-6">


                @if( $REGNRO != "")
                <input type="hidden" name="REGNRO" value="{{$REGNRO}}">
                @endif

                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">


                <label for="element_1">RUC/CÉDULA </label>
                <input name="CEDULA_RUC" class="form-control" type="text" maxlength="15" value="{{$CEDULA_RUC}}" />

                <label for="element_2">RAZÓN SOCIAL </label>
                <input name="NOMBRE" class="form-control" type="text" maxlength="80" value="{{$NOMBRE}}" />

                <label for="element_3">DOMICILIO </label>
                <input name="DIRECCION" class="form-control" type="text" maxlength="80" value="{{$DIRECCION}}" />

                <label for="element_4">CIUDAD </label>
                <x-city-chooser  name="CIUDAD" clase="form-control" style=""  :value="$CIUDAD"  />
                



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
                <button type="submit" class="btn btn-warning btn-sm">Guardar</button>
            </div>


        </div> 
</div>
@include("validations.form_validate")
@include("validations.formato_numerico")



<script>
    function phone_input(ev) {
        if (ev.data == null) return;

        if ((ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57) && ev.data.charCodeAt() != 32) {
            ev.target.value =
                ev.target.value.substr(0, ev.target.selectionStart - 1) + " "
            ev.target.value.substr(ev.target.selectionStart);
        }
    }



    async function get_ciudades() {
        let req = await fetch("<?= url("ciudades") ?>");
        let json_r = await req.json();


        let departs = json_r.map(
            function(obje) {
                return obje.departa;
            }
        ).filter(function(obj, indice, arr) {

            return arr.indexOf(obj) == indice;
        });


        let ordenado = departs.map(function(key) {
            let cities = json_r.filter(function(obj_ciu) {
                return obj_ciu.departa == key;
            }).map(function(nuevo) {
                return {
                    regnro: nuevo.regnro,
                    ciudad: nuevo.ciudad
                };
            });
            return {
                [key]: cities
            };
        });

        ordenado.forEach(function(regi) {

            let depart = Object.keys(regi)[0];
            let ciudades = regi[depart];
            let str_ciudades = ciudades.map(function(citi) {
                return "<option value='" + citi.regnro + "'>" + citi.ciudad + "</option>";
            }).join();

            let optgr = "<optgroup label='" + depart + "'>" + str_ciudades + "</optgroup>";
            //clasificar
            $("select[name=CIUDAD]").append(optgr);
        });

        /* */
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



    async function guardarProveedor(ev) {

        ev.preventDefault();

        formValidator.init(ev.target);

        let payload = formValidator.getData();

        let metodo=   $("#FORM-PROVEEDOR input[name=_method]").val();
        show_loader();
        let req = await fetch(ev.target.action, {
            "method":   metodo,
            headers: {

                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: payload
        });
        let resp = await req.json();
        hide_loader();
        if ("ok" in resp) {
            //En el ok traera el ultimo id registrado

            window.ULTIMO_PROVEEDOR = {
                REGNRO: resp.ok,
                NOMBRES: $("#FORM-PROVEEDOR input[name=NOMBRE]").val()
            };
            alert("Registrado ");

            let redireccion=  $("#REDIRECCION").val();
            if(  redireccion ==  "S") window.location= "<?=url("proveedores")?>";
            
            if ("cerrarMyModal" in window) cerrarMyModal();
            
            

        } else {
            alert(resp.err);
        }


    }
</script>
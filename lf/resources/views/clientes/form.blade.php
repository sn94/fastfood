@php


$REGNRO= isset( $cliente )? $cliente->REGNRO : "";
$CEDULA_RUC= isset( $cliente )? $cliente->CEDULA_RUC : "";
$NOMBRE= isset( $cliente )? $cliente->NOMBRE : "";
$DIRECCION= isset( $cliente )? $cliente->DIRECCION : "";
$CIUDAD= isset( $cliente )? $cliente->CIUDAD : "";
$TELEFONO= isset( $cliente )? $cliente->TELEFONO : "";
$CELULAR= isset( $cliente )? $cliente->CELULAR : "";
$EMAIL= isset( $cliente )? $cliente->EMAIL : "";
 

@endphp
 

<div class="row">
            <div class="col-12 col-md-6">


                @if( $REGNRO != "")
                <input type="hidden" name="REGNRO" value="{{$REGNRO}}">
                @endif

                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">


                <label for="element_1">RUC/CÉDULA </label>
                <input name="CEDULA_RUC" class="form-control form-control-sm" type="text" maxlength="15" value="{{$CEDULA_RUC}}" />

                <label for="element_2">RAZÓN SOCIAL </label>
                <input name="NOMBRE" class="form-control form-control-sm" type="text" maxlength="80" value="{{$NOMBRE}}" />

                <label for="element_3">DOMICILIO </label>
                <input name="DIRECCION" class="form-control form-control-sm" type="text" maxlength="80" value="{{$DIRECCION}}" />

                <label for="element_4">CIUDAD </label>

                <x-city-chooser  name="CIUDAD" clase="form-control form-control-sm" style=""  :value="$CIUDAD"  />
                
                




            </div>
            <div class="col-12 col-md-6">
                <label for="element_5">TELÉFONO </label>
                <input oninput="phone_input(event)" name="TELEFONO" class="form-control form-control-sm" type="text" maxlength="20" value="{{$TELEFONO}}" />
                <label for="element_6">CELULAR </label>
                <input oninput="phone_input(event)" name="CELULAR" class="form-control form-control-sm" type="text" maxlength="20" value="{{$CELULAR}}" />

                <label for="element_7">EMAIL </label>
                <input name="EMAIL" class="form-control form-control-sm" type="text" maxlength="80" value="{{$EMAIL}}" />
            </div>


            <div class="col-12 mt-2 d-flex justify-content-center">
                 <button type="submit" class="btn fast-food-form-button">Guardar</button>
            </div>


        </div>


@include("validations.form_validate")
@include("validations.formato_numerico")





<script>
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





    function phone_input(ev) {
        if (ev.data == null) return;

        if ((ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57) && ev.data.charCodeAt() != 32) {
            ev.target.value =
                ev.target.value.substr(0, ev.target.selectionStart - 1) + " "
            ev.target.value.substr(ev.target.selectionStart);
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

    
    async function guardar(ev) {
       
        ev.preventDefault();
         
        //if (campos_vacios()) return;

        formValidator.init(ev.target);

        let payload = formValidator.getData();
 
        show_loader();
        let req = await fetch(ev.target.action, {
            "method": "POST",
            headers: {

                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: payload
        });
        let resp = await req.json();
        hide_loader();
        if ("ok" in resp) {
            alert("Guardado");
            window.ULTIMO_CLIENTE= {
                REGNRO:  resp.ok,
                NOMBRES: $("#FORM-CLIENTE input[name=NOMBRE]").val()
            };
            if( $("#REDIRECCIONAR").val()  ==  "SI")
            window.location = $("#CLIENTES-INDEX").val();

            if( "cerrarMyModal" in window)
            cerrarMyModal();
        } else {
            alert(resp.err);
        }


    }


 
</script>
@extends("templates.app_admin")


@section("content")



@php
//RECURSOS DE DATOS


$RESOURCE_URL= isset( $RESOURCE_URL ) ? $RESOURCE_URL : ""; //Listado
$RESOURCE_URL_ITEM= isset( $RESOURCE_URL_ITEM ) ? $RESOURCE_URL_ITEM : ""; //dato por cada item

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




<!--URL -->
<input type="hidden" id="RESOURCE-URL" value="{{$RESOURCE_URL}}">
<input type="hidden" id="RESOURCE-URL-ITEM" value="{{$RESOURCE_URL_ITEM}}">
<input type="hidden" id="PROVEEDOR-URL" value="{{url('proveedores')}}">



<h2 class="text-center mt-2" style="font-family: titlefont;">Depósito: Otras entradas de {{$TITULO}} </h2>





<div id="loaderplace"></div>
<div class="row m-5">
    <div class="col-12 col-md-12">
        <form action="{{$FORM_URL}}" method="POST" onsubmit="guardar(event)">

            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">


            <div class="row bg-dark mt-2 pt-1 pb-2 pr-2 pl-2 pr-md-2 pl-md-2">


                <div class="col-12  mb-1">
                    <button type="submit" class="btn btn-danger"> GUARDAR</button>
                </div>

                <!-- CABECERA  --->
                <div class="col-12 col-md-4 col-lg-4 mb-1">
                    <div style="display: grid;  grid-template-columns: 25%  75%;">
                        <label style="grid-column-start: 1;" class="mt-1" for="element_7">PROVEEDOR </label>
                        <input type="hidden" name="PROVEEDOR">
                        <input oninput="if(this.value=='') document.querySelector('input[name=PROVEEDOR]').value='' ; " style="grid-column-start: 2;" class="form-control proveedor mt-1" type="text" />
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-4 mb-1">
                    <div style="display: grid;  grid-template-columns: 30%  70%;">
                        <label style="grid-column-start: 1;" class="mt-1" for="element_7">FACTURA N°: </label>
                        <input name="NUMERO" style="grid-column-start: 2;" class="form-control mt-1" type="text" />
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-4 mb-1">
                    <div style="display: grid;  grid-template-columns: 20%  80%;">
                        <label style="grid-column-start: 1;" class="mt-1" for="element_7">FECHA: </label>
                        <input value="{{date('Y-m-d')}}" name="FECHA" style="grid-column-start: 2;" class="form-control mt-1" type="date" />
                    </div>
                </div>

                <div class="col-12 col-md-12  mb-1">
                    <div style="display: grid;  grid-template-columns: 20%  80%;">
                        <label style="grid-column-start: 1;" class="mt-1" for="element_7">OTRO CONCEPTO: </label>
                        <input  name="CONCEPTO" style="grid-column-start: 2;" class="form-control mt-1" type="text" />
                    </div>
                </div>


                <!-- 
                     
                end
                 
                CABECERA  --->



                <div class="col-12 col-md-12 col-lg-4 mb-1">
                    <div style="display: grid;  grid-template-columns: 10% 20% 70%;">

                        <label style="grid-column-start: 1;   font-size: 20px;  ">ITEM:</label>

                        <input style="grid-column-start: 2; font-weight: 600; color: black; background-color: #a8fb37 !important;" class="form-control" type="text" id="ITEM-ID" readonly>

                        <input style="grid-column-start: 3;" class="form-control" style="width: 100% !important; height: 40px;" autocomplete="off" type="text" id="ITEM" placeholder="Buscar por descripcion">

                    </div>
                </div>

                <div class="col-12  col-md-6 col-lg-3 mb-1">
                    <div style="display: grid;  grid-template-columns: 25% 40% 35%;">
                        <label style="grid-column-start: 1;">CANTIDAD: </label>
                        <input oninput="formatear_decimal(event)" style="grid-column-start: 2;" id="CANTIDAD" class="form-control" type="text" />
                        <label style="color: yellow;grid-column-start: 3;" id="MEDIDA"></label>
                    </div>
                </div>

                <div class="col-12 col-md-4  col-lg-3 mb-1">
                    <div style="display: grid;  grid-template-columns: 30%  70%; ">
                        <label style="grid-column-start: 1;">PRECIO: </label>
                        <input numerico="yes" oninput="formatear_entero(event)" style="grid-column-start: 2;" id="PRECIO" class="form-control text-right" type="text" />
                        <input type="hidden" id="IVA"> </label>
                    </div>
                </div>

                <div class="col-12 col-md-2   col-lg-2 mt-2 d-flex justify-content-center  align-items-center ">
                    <button type="button" onclick="cargar_tabla()" class="btn btn-warning btn-lg">CARGAR</button>
                </div>


            </div>
            <div class="row bg-dark mt-2 pt-1 pb-2 pr-2 pl-2 pr-md-2 pl-md-2">
                <div class="col-12 col-md-12 p-0">
                    <table class="table table-striped table-secondary text-dark">

                        <thead>
                            <tr style="font-family: mainfont;font-size: 18px;">
                                <th>DESCRIPCIÓN</th>
                                <th class='text-right'>PRECIO</th>
                                <th>UNI.MED.</th>
                                <th> CANTIDAD</th>
                                <th class='text-right'>EXENTA</th>
                                <th class='text-right'>5 %</th>
                                <th class='text-right'>10 %</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="COMPRA-DETALLE">

                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4">
                                </th>
                                <th class='text-right' id="TOTALEXE">0</th>
                                <th class='text-right' id="TOTAL5">0</th>
                                <th class='text-right'  id="TOTAL10">0</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>



            </div>
        </form>

    </div>
</div>




<script>
    var compra_model = [];


    var mostrar_item = async function(id) {
        let item = await get_item(id);
        if (Object.keys(item).length > 0) {
            // let PRECIO=   item.PVENTA;
            $("#MEDIDA").text(item.MEDIDA);
            $("#IVA").val(item.TRIBUTO);

        }
    }

    var get_item = async function(id) {
        let url_ = $("#RESOURCE-URL-ITEM").val() + "/" + id;
        let req = await fetch(url_, {
            headers: {
                formato: "json"
            }
        });
        let resp = await req.json();
        if ("ok" in resp) {
            return resp.ok;
        } else return {};
    };










    /**Cargar a la tabla  */


    function deleteme(esto) {

        let row = $(esto.parentNode.parentNode);
        let id = row.attr("id");
        $(esto.parentNode.parentNode).remove();
        //quitar del modelo 
        compra_model = compra_model.filter(function(arg) {


            return String(arg.ITEM) != String(id);
        });
    }

    function limpiar_campos_cabecera() {
        $("input[name=PROVEEDOR]").val("");
        $(".proveedor").val("");
        $("input[name=FECHA]").val("");
        $("input[name=NUMERO]").val("");
    }


    function limpiar_campos_detalle() {
        $("#ITEM-ID").val("");
        $("#ITEM").val("");
        $("#PRECIO").val("");
        $("#CANTIDAD").val("");
        $("#MEDIDA").text("");
        $("#IVA").val("");
    }


    function limpiar_tabla() {
        $("#COMPRA-DETALLE").html("");
    }

    function actualizar_fila(objec) {
        let existe = compra_model.map((ar) => ar.ITEM).filter((ar) => parseInt(ar) == parseInt(objec.ITEM)).length;
        if (existe > 0) {
            let numero_de_filas = $("#COMPRA-DETALLE tr").length;
            if (numero_de_filas == 0) return false;

            for (let nf = 0; nf < numero_de_filas; nf++) {
                let lafila = $("#COMPRA-DETALLE tr")[nf];
                console.log(lafila);
                if (String(lafila.id) == String(objec.ITEM)) {
                    $(lafila).remove();
                    break;
                }
            }
        }
    }


    function calcular_totales(){

        let totales1= compra_model.map(  (ar)=>  ar.EXENTA).reduce( function(suma, elemento){ console.log(   suma);
            return suma + elemento;
        } )
        let totales2= compra_model.map(  (ar)=>  ar.IVA5).reduce( function(suma, elemento){ console.log(   suma);
            return suma+ elemento;
        } );
        let totales3= compra_model.map(  (ar)=>  ar.IVA10).reduce( function(suma, elemento){ console.log(   suma);
            return suma+ elemento;
        } );
        $("#TOTALEXE").text(  dar_formato_millares( totales1) );
        $("#TOTAL5").text( dar_formato_millares( totales2) );
        $("#TOTAL10").text( dar_formato_millares( totales3) );
    }

    function cargar_tabla() {

        let regnro = $("#ITEM-ID").val();
        let descri = $("#ITEM").val();
        let precio = limpiar_numero_($("#PRECIO").val());
        let canti = $("#CANTIDAD").val();
        let medida = $("#MEDIDA").text();
        let iva = parseInt($("#IVA").val());
        let subtotal = precio * parseInt(canti);

        let exe = iva == 0 ? subtotal : 0;
        let i5 = iva == 5 ? subtotal : 0;
        let i10 = iva == 10 ? subtotal : 0;

        if (regnro != "" && precio != "" && canti != "") {

            let des = "<td> " + descri + "</td>";
            let prec = "<td class='text-right' >    " + dar_formato_millares(precio) + "</td>";
            let med = "<td>     " + medida + "</td>";
            let cant = "<td>    " + canti + "</td>";
            let ex = "<td class='text-right' >    " + dar_formato_millares(exe) + "</td>";
            let iv5 = "<td class='text-right' >    " + dar_formato_millares(i5) + "</td>";
            let iv10 = "<td class='text-right' >    " + dar_formato_millares(i10) + "</td>";
            let del = "<td> <a style='color:black;' href='#' onclick='deleteme( this )'> <i class='fa fa-trash'></i> </a>  </td>";

            //agregar al modelo
            let objc = {
                ITEM: regnro,
                CANTIDAD: canti,
                P_UNITARIO: precio,
                EXENTA: exe,
                IVA5: i5,
                IVA10: i10
            };
            compra_model.push(objc);
            actualizar_fila(objc);
            $("#COMPRA-DETALLE").append("<tr id='" + regnro + "' >" + des + prec + med + cant + ex + iv5 + iv10 + del + "</tr>");
            limpiar_campos_detalle();
            calcular_totales();
        } else
            alert("Seleccione un item antes de cargar, o complete todos los datos");

    }







    /**Validaciones */

    function dar_formato_millares(val_float) {
        return new Intl.NumberFormat("de-DE").format(val_float);
    }


    function limpiar_numero_para_float(val) {
        return val.replaceAll(new RegExp(/[.]*/g), "").replaceAll(new RegExp(/[,]{1}/g), ".");
    }


    function limpiar_numero_(valor) {
        return valor.replaceAll(new RegExp(/[.]*/g), "");
    }

    function limpiar_numero(val) {
        let nro_campos_a_limp = $("[numerico=yes]").length;

        for (let ind = 0; ind < nro_campos_a_limp; ind++) {
            let valor = $("[numerico=yes]")[ind].value;
            let valor_purifi = valor.replaceAll(new RegExp(/[.]*/g), "");
            $("[numerico=yes]")[ind].value = valor_purifi;
        }
        //return val.replaceAll(new RegExp(/[.]*/g), "");
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


















    async function guardar(ev) {
        //config_.processData= false; config_.contentType= false;
        ev.preventDefault();
        show_loader();
        //componer
        let cabecera = {
            PROVEEDOR: $("input[name=PROVEEDOR]").val(),
            FECHA: $("input[name=FECHA]").val(),
            NUMERO: $("input[name=NUMERO]").val()
        }
        let detalle = compra_model;
        let req = await fetch(ev.target.action, {
            "method": "POST",

            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                CABECERA: cabecera,
                DETALLE: detalle
            })
        });
        let resp = await req.json();
        hide_loader();
        if ("ok" in resp) {
            limpiar_campos_cabecera();
            limpiar_tabla();
            alert(resp.ok);
            //window.location.reload();
        } else {
            alert(resp.err);
        }


    }



    function show_loader() {
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif ") ?>'   />";
        $("#loaderplace").html(loader);
    }

    function hide_loader() {
        $("#loaderplace").html("");
    }



    //Autocomplete
    async function autocompletado_proveedores() {
        let url_ = $("#PROVEEDOR-URL").val();
        let req = await fetch(url_, {
            headers: {
                formato: "json"
            }
        });
        let resp = await req.json();

        var dataArray = resp.map(function(value) {
            return {
                label: value.NOMBRE,
                value: value.REGNRO
            };
        });

        let elementosCoincidentes = document.querySelectorAll(".proveedor");

        Array.prototype.forEach.call(elementosCoincidentes, function(input) {
            new Awesomplete(input, {
                list: dataArray,
                // insert label instead of value into the input.
                replace: function(suggestion) {
                    this.input.value = suggestion.label;
                    $("input[name=PROVEEDOR]").val(suggestion.value);
                }
            });
        });

    }






    //Autocomplete
    async function autocompletado_items() {
        let url_ = $("#RESOURCE-URL").val();
        let req = await fetch(url_, {
            method: "POST",

            headers: {
                formato: "json",
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: "tipo=P" /** preventa parametro solo valido para buscar productos */
        });
        let resp = await req.json();

        var dataArray = resp.map(function(value) {
            return {
                label: value.DESCRIPCION,
                value: value.REGNRO
            };
        });

        let elementosTARGET = document.getElementById("ITEM");

        new Awesomplete(elementosTARGET, {
            list: dataArray,
            // insert label instead of value into the input.
            replace: function(suggestion) {
                this.input.value = suggestion.label;
                $("#ITEM-ID").val(suggestion.value);
                mostrar_item(suggestion.value);
            }
        });

    }



    window.onload = function() {
        autocompletado_items();
        autocompletado_proveedores();
    };
</script>

@endsection
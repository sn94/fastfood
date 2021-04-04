@extends("templates.admin.index")


@section("content")



@php

@endphp




@include("deposito.remision_de_terminados.estilos")



<!--URL -->
<input type="hidden" id="RESOURCE-URL" value="{{url('productos/buscar')}}">
<input type="hidden" id="RESOURCE-URL-ITEM" value="{{url('productos/get')}}">
<input type="hidden" id="PROVEEDOR-URL" value="{{url('proveedores')}}">



<h2 class="text-center mt-2" style="font-family: titlefont;">Remisión de productos terminados</h2>


<div id="loaderplace"></div>
<div class="row m-5">
    <div class="col-12 col-md-12">
        <form action="<?= url("deposito/remision-productos-terminados") ?>" method="POST"  onkeypress="if(event.keyCode == 13) event.preventDefault();"    onsubmit="guardar(event)">

            @if( isset($PRODUCCION))
            <input type="hidden" name="PRODUCCION_ID" value="{{$PRODUCCION->REGNRO}}">
            @endif
            <input type="hidden" name="REGISTRADO_POR" value="<?= session("ID") ?>">
            <input type="hidden" name="SUCURSAL" value="<?= session("SUCURSAL") ?>">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">



            <div class="row bg-dark mt-2 pt-1 pb-2 pr-2 pl-2 pr-md-2 pl-md-2">




                <div class="col-12 col-md-6">
                    @includeIf('deposito.remision_de_terminados.ficha_produccion_view', ['PRODUCCION' => $PRODUCCION])

                    <div class="row">
                        <div class="col-12 col-md-3  mb-1">
                            <label class="mt-1" for="element_7">FECHA: </label>
                            <input value="{{date('Y-m-d')}}" name="FECHA" class="form-control mt-1" type="date" />

                        </div>

                        <div class="col-12 col-md-9  mb-1">
                            <label class="mt-1" for="element_7">OBSERVACIÓN: </label>
                            <input name="CONCEPTO" class="form-control mt-1" type="text" />

                        </div>
                        <div class="col-12 col-md-2  mb-1">
                            <button type="submit" class="btn btn-danger"> GUARDAR</button>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-6">
                    @include("deposito.remision_de_terminados.grill")
                </div>


            </div>





        </form>

    </div>
</div>


@include("validations.form_validate")
@include("validations.formato_numerico")

<script>
    /**Cargar a la tabla  */







    async function guardar(ev) {
        //config_.processData= false; config_.contentType= false;
      
         ev.preventDefault();
        
        formValidator.init(ev.target);
        if ($("#REMISION-DETALLE").children().length == 0) {
            alert("Cargue al menos un item");
            return;
        }
        show_loader();
        //componer
        let cabecera = formValidator.getData("application/json");
        /*let cabecera = {
            REGISTRADO_POR: $("input[name=REGISTRADO_POR]").val(),
            CONCEPTO: $("input[name=CONCEPTO]").val(),
            FECHA: $("input[name=FECHA]").val(),
            NUMERO: $("input[name=NUMERO]").val()
        }*/
        let detalle = remision_model;
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

            formValidator.limpiarCampos();
            $("input[name=FECHA]").val((new Date()).getFecha());
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




    async function restaurar_modelo_remision() {
        //item cantidad tipo medida
        let row = document.querySelectorAll("#REMISION-DETALLE tr");
        if (row.length == 0) return;

        let modelo = Array.prototype.map.call(row, function(domtr) {


            let ITEM = domtr.id;
            let CODIGO= domtr.children[0].textContent;
            let CANTIDAD = domtr.children[3].textContent;
            let MEDIDA = domtr.children[2].textContent;
            let TIPO = domtr.className.split("-")[0];
            return {
                CODIGO: CODIGO,
                ITEM: ITEM,
                CANTIDAD: CANTIDAD,
                MEDIDA: MEDIDA,
                TIPO: TIPO
            };
        });
        remision_model = modelo;
    }







    window.onload = async function() {
        /* autocompletado_items();
         autocompletado_proveedores();*/


        await restaurar_modelo_remision();
        //modal
        $('.modal').on('hidden.bs.modal', function(e) {
            $("#ITEM-ID").val(buscador_items_modelo.REGNRO);
            $("#ITEM").val(buscador_items_modelo.DESCRIPCION);
            $("#MEDIDA").text(buscador_items_modelo.MEDIDA);
            //cargar_tabla();
        });



        //formato entero
        let enteros = document.querySelectorAll(".entero");
        Array.prototype.forEach.call(enteros, function(inpu) {
            inpu.oninput = formatoNumerico.formatearEntero;
            $(inpu).addClass("text-end");
        });


        let decimales = document.querySelectorAll(".decimal");
        Array.prototype.forEach.call(decimales, function(inpu) {
            inpu.oninput = formatoNumerico.formatearDecimal;
            $(inpu).addClass("text-end");
        });

    };
</script>

@endsection
@extends("templates.admin.index")


@section("content")



@php

@endphp




@include("deposito.nota_residuos.estilos")



<!--URL -->
<input type="hidden" id="RESOURCE-URL" value="{{url('stock/buscar')}}">
<input type="hidden" id="RESOURCE-URL-ITEM" value="{{url('stock/get')}}">
<input type="hidden" id="PROVEEDOR-URL" value="{{url('proveedores')}}">






<div class="container-fluid bg-dark text-light col-12 col-md-11 px-2">

<h2 class="text-center mt-2"  >Nota de residuos</h2>
<div id="loaderplace"></div>
 
        <form action="<?= url("nota-residuos/create") ?>" method="POST"  onkeypress="if(event.keyCode == 13) event.preventDefault();"    onsubmit="guardar(event)">

            @if( isset($PRODUCCION))
            <input type="hidden" name="PRODUCCION_ID" value="{{$PRODUCCION->REGNRO}}">
            @endif
            <input type="hidden" name="REGISTRADO_POR" value="<?= session("ID") ?>">
            <input type="hidden" name="SUCURSAL" value="<?= session("SUCURSAL") ?>">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
<div class="container-fluid">
<div class="row  mt-2 pt-1 pb-2">
                <div class="col-12 col-md-6">
                    @includeIf('deposito.nota_residuos.ficha_produccion_view', ['PRODUCCION' => $PRODUCCION])

                    <div class="row">
                        <div class="col-12 col-md-3  mb-1">
                            <label class="mt-1" for="element_7">FECHA: </label>
                            <input value="{{date('Y-m-d')}}" name="FECHA" class="form-control mt-1" type="date" />
                        </div>
                        <div class="col-12 col-md-9  mb-1">
                            <label  class="mt-1" for="element_7">OBSERVACIÓN: </label>
                            <input name="CONCEPTO"  class="form-control mt-1" type="text" />
                        </div>
                        <div class="col-12 col-md-2  mb-1">
                            <button type="submit" class="btn btn-danger"> GUARDAR</button>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    @include("deposito.nota_residuos.grill")
                </div>
            </div> 
</div>
        </form>
 
</div>


@include("validations.form_validate")
@include("validations.formato_numerico")

<script>
    /**Cargar a la tabla  */







    async function guardar(ev) {
        //config_.processData= false; config_.contentType= false;
       
     ev.preventDefault();
         
        
        formValidator.init(ev.target);
        if ($("#RESIDUOS-DETALLE").children().length == 0) {
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
        let detalle = residuos_model;
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









    window.onload = function() {
        autocompletado_items();
        autocompletado_proveedores();


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
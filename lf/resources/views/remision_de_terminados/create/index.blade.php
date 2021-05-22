@extends("templates.admin.index")


@section("content")



@php

//Url para procesar el form 
$urlAction=  isset( $REMISION)  ?  url("remision-prod-terminados/update")  :  url("remision-prod-terminados/create");

$REGNRO= isset($REMISION) ? $REMISION->REGNRO : '';
$NUMERO= isset($REMISION) ? $REMISION->NUMERO : '';
$FECHA= isset($REMISION) ? $REMISION->FECHA : date('Y-m-d');
$AUTORIZADO_POR= isset($REMISION) ? $REMISION->AUTORIZADO_POR : '';
$CONCEPTO= isset($REMISION) ? $REMISION->CONCEPTO : '';
$PRODUCCION_ID= isset($REMISION) ? $REMISION->PRODUCCION_ID : ( isset($PRODUCCION) ? $PRODUCCION->REGNRO : '');
@endphp




@include("remision_de_terminados.create.estilos")
<!-- incluye funciones para generar y usar un buscador de datos -->
@include("buscador.Buscador")



<div id="loaderplace"></div>

<div class="container-fluid bg-dark text-light col-12 col-md-11 col-lg-8  mx-auto pb-5">
    <h2 class="text-center mt-2">Remisión de productos terminados</h2>

    <form action="<?= $urlAction ?>" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)">

        <input type="hidden" name="PRODUCCION_ID" value="{{$PRODUCCION_ID}}">
        <input type="hidden" name="REGISTRADO_POR" value="<?= session("ID") ?>">
        <input type="hidden" name="SUCURSAL" value="<?= session("SUCURSAL") ?>">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">



        <div class="row  mt-2 pt-1 pb-2 pr-2 pl-2 pr-md-2 pl-md-2">

            <div class="col-12 ">
                @if( isset($PRODUCCION))
                @includeIf('remision_de_terminados.create.ficha_produccion_view', ['PRODUCCION' => $PRODUCCION])
                @endif


                <div class="row">

                    @if( isset($REMISION))
                    <div class="col-12 col-md-2  mb-1">
                        <label class="mt-1 fs-6" for="element_7">Nota N°: </label>
                        <input name="REGNRO" class="form-control mt-1 fs-6 text-center" type="text"  readonly value="{{$REGNRO}}" />
                    </div>
                    @endif

                    <div class="col-12 col-md-3  mb-1">
                        <label class="mt-1 fs-6" for="element_7">Número doc.: </label>
                        <input name="NUMERO" value="{{$NUMERO}}" class="form-control mt-1 fs-6" type="text" maxlength="15" />

                    </div>

                    <div class="col-12 col-md-3  mb-1">
                        <label class="mt-1 fs-6" for="element_7">Fecha: </label>
                        <input value="{{$FECHA}}" name="FECHA" class="form-control mt-1 fs-6" type="date" />

                    </div>

                    <div class="col-12 col-md-6  mb-1">
                        <label class="mt-1 fs-6" for="element_7">Autorizado Por: </label>
                        <input maxlength="50" value="{{$AUTORIZADO_POR}}" name="AUTORIZADO_POR" class="form-control mt-1 fs-6" type="text" />
                    </div>

                    <div class="col-12 col-md-5  mb-1">
                        <label class="mt-1 fs-6" for="element_7">Observación: </label>
                        <input value="{{$CONCEPTO}}" name="CONCEPTO" class="form-control mt-1 fs-6" type="text" />
                    </div>
                    <div class="col-12 col-md-2  mb-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-danger"> GUARDAR</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 ">
                @include("remision_de_terminados.create.grill")
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
            

            window.location = "<?= url('remision-prod-terminados/index') ?>";
        } else {
            alert(resp.err);
        }


    }





    async function restaurar_modelo_remision() {
        //item cantidad tipo medida
        let row = document.querySelectorAll("#REMISION-DETALLE tr");
        if (row.length == 0) return;

        let modelo = Array.prototype.map.call(row, function(domtr) {


            let ITEM = domtr.id;
            let CODIGO = domtr.children[0].textContent;
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
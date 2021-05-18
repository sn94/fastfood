@extends("templates.admin.index")


@section("content")




@include("nota_residuos.create.estilos")

<!-- incluye funciones para generar y usar un buscador de datos -->
@include("buscador.Buscador")





<div class="container-fluid bg-dark text-light col-12 col-md-10 col-lg-8 px-5 pb-5">

    <h2 class="text-center mt-2">Nota de residuos</h2>
    <div id="loaderplace"></div>

    <form action="<?= url("nota-residuos/create") ?>" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)">

        @if( isset($PRODUCCION))
        <input type="hidden" name="PRODUCCION_ID" value="{{$PRODUCCION->REGNRO}}">
        @endif
        <input type="hidden" name="created_by" value="<?= session("ID") ?>">
        <input type="hidden" name="SUCURSAL" value="<?= session("SUCURSAL") ?>">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <div class="container-fluid">
            <div class="row  mt-2 pt-1 pb-2">
                <div class="col-12">

                    @if( isset($PRODUCCION) )
                    @includeIf('nota_residuos.create.ficha_produccion_view', ['PRODUCCION' => $PRODUCCION])
                    @endif

                    <div class="row">
                        <div class="col-12 col-md-3  mb-1">
                            <label class="mt-1 fs-6" for="element_7">Ficha de producción N°: </label>
                          <div class="d-flex flex-row">
                          <input readonly name="PRODUCCION_ID" id="PRODUCCION_ID" class="form-control mt-1 fs-6" type="text" maxlength="15" />
                            <a href="#" onclick="buscarFichaProduccion()"><i class="fa fa-search"></i></a>
                          </div>
                        </div>
                   
                        <div class="col-12 col-md-3  mb-1">
                            <label class="mt-1 fs-6" for="element_7">Nota N°: </label>
                            <input name="NUMERO" class="form-control mt-1 fs-6" type="text" maxlength="15" />

                        </div>
                        <div class="col-12 col-md-3  mb-1">
                            <label class="mt-1 fs-6" for="element_7">Fecha: </label>
                            <input value="{{date('Y-m-d')}}" name="FECHA" class="form-control mt-1 fs-6" type="date" />
                        </div>
                        <div class="col-12 col-md-4  mb-1">
                            <label class="mt-1 fs-6" for="element_7">Registrado Por: </label>
                            <input maxlength="50" name="ELABORADO_POR" class="form-control mt-1 fs-6" type="text" />
                        </div>
                        <div class="col-12 col-md-5  mb-1">
                            <label class="mt-1 fs-6" for="element_7">observación: </label>
                            <input name="CONCEPTO" class="form-control mt-1  fs-6" type="text" />
                        </div>
                        <div class="col-12 col-md-2  mb-1  d-flex align-items-end">
                            <button type="submit" class="btn btn-danger"> GUARDAR</button>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-12 ">
                    @include("nota_residuos.create.grill")
                </div>
            </div>
        </div>
    </form>

</div>


@include("validations.form_validate")
@include("validations.formato_numerico")

<script>
 
    async function buscarFichaProduccion() {
  
Buscador.url = "<?= url("ficha-produccion/index") ?>" ;
Buscador.htmlFormForParams = `<form> <input name='FECHA' type='date' onchange='Buscador.filtrar()' > </form>`;

Buscador.htmlFormFieldNames = ['FECHA'];
Buscador.columnNames = ["REGNRO", "ELABORADO_POR"];
Buscador.columnLabels = ['ID', 'REGISTRADO POR'];
Buscador.callback = function(respuesta) {

    const {
        REGNRO,
        ELABORADO_POR
    } = respuesta;

    window.buscador_items_modelo = respuesta;
    console.log( respuesta);
   
    $("#PRODUCCION_ID").val(REGNRO);
   /* $(PANEL_ID + " .ITEM").val(DESCRIPCION);
    $(PANEL_ID + " .MEDIDA").text(unidad_medida.UNIDAD);*/
};
Buscador.render();
}





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
            window.location = "<?= url("nota-residuos/index") ?>";
        } else {
            alert(resp.err);
        }


    }









    window.onload = function() {



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
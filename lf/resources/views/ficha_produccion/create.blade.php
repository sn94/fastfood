@extends("templates.admin.index")


@section("content")



@php


$PRODUCCION_ID= isset( $PRODUCCION_ID )? $PRODUCCION_ID : "";
$SUCURSAL_REG= isset( $PRODUCCION) ? $PRODUCCION->SUCURSAL: session("SUCURSAL");
$REGISTRADO_POR= isset( $PRODUCCION) ? $PRODUCCION->REGISTRADO_POR: session("ID");
$RECIBIDO_POR= isset( $PRODUCCION) ? session("ID") : "";

@endphp




<style>
    input:disabled {
        background-color: var(--color-14) !important;
    }

    .MEDIDA {
        color: #222;
        font-weight: 600;
    }

    a i.fa-search,
    a i.fa-download {
        background-color: var( --color-4);
        border-radius: 30px;
        padding: 5px;
        border: 1px solid black;
        color: black;
    }

    .form-control {
    
        height: 25px !important;
        font-size: 14px;
    }



    table.PRODUCCION thead tr,
    table.PRODUCCION tbody tr {
        display: grid;
        grid-template-columns: 15% 50% 15% 15% 5%;
    }




    table.PRODUCCION tbody tr td select,
    table tbody tr td input {
        height: 25px;
        ;
    }

    table.PRODUCCION thead th {
        padding: 0px;
    }

    table.PRODUCCION th,
    table.PRODUCCION td {
        font-weight: 600;
        padding: 0px;
    }


    label {
        font-size: 14px !important;
        color: black;
        font-family: Arial, Helvetica, sans-serif !important;
    }


    fieldset {
        padding: 2px !important;
        background-color: var(--color-14);
        border-radius: 8px 8px ;
    }


    legend {
        font-size: 1rem;
        color: #222;
        background-color: var(--color-4);
        border-radius: 8px 8px 0px 0px;
        text-align: center;
        font-weight: 600;
    }


    .fa-search {
        color: black;
    }
</style>




<!--URL -->
<input type="hidden" id="RESOURCE-URL" value="{{url('productos/buscar')}}">
<input type="hidden" id="RESOURCE-URL-ITEM" value="{{url('productos/get')}}">
<input type="hidden" id="PROVEEDOR-URL" value="{{url('proveedores')}}">








<div class="container-fluid pb-5 ">
    <div class="container col-12 col-md-12 bg-dark text-light pb-5 ">
        <div id="loaderplace"></div>
        <h2 class="text-center mt-2">Ficha de producción</h2>



        @if( $PRODUCCION_ID != '' )
        <form id="PRODUCCIONFORM" action="<?= url("ficha-produccion") ?>" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)">
            <input type="hidden" name="_method" value="PUT">
            @else
            <form action="<?= url("ficha-produccion") ?>" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)">
                @endif


                <input type="hidden" name="SUCURSAL" value="<?= $SUCURSAL_REG ?>">
                <input type="hidden" name="REGISTRADO_POR" value="<?= $REGISTRADO_POR ?>">
                <input type="hidden" name="RECIBIDO_POR" value="<?= $RECIBIDO_POR ?>">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">


                @if( $PRODUCCION_ID != '' )
                <input type="hidden" id="PRODUCCION_ID" name="REGNRO" value="{{$PRODUCCION_ID}}">
                @endif


                <div class="col-12 col-md-2  mb-1">
                    <button type="submit" class="btn btn-danger"> GUARDAR FICHA</button>
                </div>
                @include("ficha_produccion.header")

                <div class="row">
                    <div class="col-12 col-md-6">
                        @include("ficha_produccion.grill_productos")
                    </div>
                    <div class="col-12 col-md-6">
                        @include("ficha_produccion.grill_ingredientes")
                    </div>

                    <div class="col-12 col-md-6 mt-1">
                        @include("ficha_produccion.grill_insumos")
                    </div>
                </div>

            </form>

    </div>
</div>


@include("validations.form_validate")
@include("validations.formato_numerico")
@include("ficha_produccion.cargador_tabla")
@include("buscador.Buscador")


<script>
    /**Cargar a la tabla  */







    async function guardar(ev) {
        //config_.processData= false; config_.contentType= false;


        ev.preventDefault();


        formValidator.init(ev.target);

        if ($("#PRODUCTOS-PANEL .DETALLE").children().length == 0) {
            alert("Cargue al menos un item");
            return;
        }

        show_loader();
        //componer
        let cabecera = formValidator.getData("application/json");
        let detalle1 = productos_controller.data_model;
        let detalle2 = ingredientes_controller.data_model;
        let detalle3 = insumos_controller.data_model;

        let req = await fetch(ev.target.action, {
            "method": "POST",

            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                CABECERA: cabecera,
                PRODUCTOS: detalle1,
                INGREDIENTES: detalle2,
                INSUMOS: detalle3
            })
        });
        let resp = await req.json();
        hide_loader();
        if ("ok" in resp) {

            /* formValidator.limpiarCampos();
            $("input[name=FECHA]").val((new Date()).getFecha());
            ingredientes_controller.limpiar_tabla();
            productos_controller.limpiar_tabla();
            insumos_controller.limpiar_tabla();
            alert(resp.ok);
*/
            window.location.reload();
        } else {
            alert(resp.err);
        }


    }


 







    async function restaurar_ficha_produccion() {
        let produccionId = $("#PRODUCCION_ID").val();
        if (produccionId != "") {

            let url__ = "<?= url('ficha-produccion') ?>/" + produccionId;
            let req = await fetch(url__, {
                headers: {
                    formato: "json"
                }
            });
            let resp = await req.json();

            //Restaurar modelo
            //cabecera:   regnro  fecha  estado sucursal registrado_por actualizado_por  
            //detalle: regnro descripcion produccion_id item cantidad  tipo

            let productos_data = resp.DETALLE.filter(function(arg) {
                return arg.TIPO == "PE";
            });
            let ingredien_data = resp.DETALLE.filter(function(arg) {
                return arg.TIPO == "MP";
            });
            let activofijo_data = resp.DETALLE.filter(function(arg) {
                return arg.TIPO == "AF";
            });

            //productos a elaborar
            productos_data.forEach(productos_controller.actualiza_modelo_de_datos.bind(productos_controller));
            productos_data.forEach(productos_controller.cargar_tabla.bind(productos_controller));
            //ingredientes
            ingredien_data.forEach(ingredientes_controller.actualiza_modelo_de_datos.bind(ingredientes_controller));
            ingredien_data.forEach(ingredientes_controller.cargar_tabla.bind(ingredientes_controller));
            //mobiliario, utiles, otros
            activofijo_data.forEach(insumos_controller.actualiza_modelo_de_datos.bind(insumos_controller));
            activofijo_data.forEach(insumos_controller.cargar_tabla.bind(insumos_controller));

        }
        return;
    }



    function abrirBuscadorStock() {
        //KEY:'#PROVEEDOR-KEY',NAME:'#PROVEEDOR-NAME

        let tipoSTOCK = [{
            "MP": "MATERIA PRIMA"
        }, {
            "PP": "PARA VENTA"
        }, {
            "PE": "PRODUCTO ELABORADO"
        }, {
            "AF": "MOBILIARIO Y OTROS"
        }];
        let formHtml = `
        <form>
    <select class='form form-control' name='TIPO'  onchange='Buscador.filtrar()' >
    ${ tipoSTOCK.map(  ( arg)=>{  
        const [clave, valor ]= Object.entries( arg)[0] ;
        return     `<option value='${clave}' >${valor}</option>`} )  
     }</select>
     </form>
    `;

        Buscador.url = "<?= url("stock/buscar/ALL") ?>";
        Buscador.htmlFormForParams = formHtml;
        Buscador.htmlFormFieldNames = ['TIPO'];
        Buscador.columnNames = ["REGNRO", "DESCRIPCION"];
        Buscador.columnLabels = ['ID', 'DESCRIPCION'];
        Buscador.callback = function(seleccionado) {

            console.log("Elegiste", seleccionado);
            /*   $('#PROVEEDOR-KEY').val( seleccionado.REGNRO);
               $('#PROVEEDOR-NAME').val( seleccionado.NOMBRE);*/
        };
        Buscador.render();
    }



    window.onload = function() {


        init_ingredientes();
        init_productos();
        init_insumos();

        restaurar_ficha_produccion();

        formatoNumerico.formatearCamposNumericosDecimales("PRODUCCIONFORM");


    };
</script>

@endsection
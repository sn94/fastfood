@extends("templates.admin.index")


@section("content")


@php


//Datos de compras

$SUCURSAL= isset($COMPRA) ? $COMPRA->SUCURSAL : session("SUCURSAL");
$REGISTRADO_POR= isset($COMPRA) ? $COMPRA->REGISTRADO_POR : session("ID") ;

@endphp


<style>
    .form-control.form-compra {
        background: white !important;
        color: black !important;
        height: 25px !important;
        font-size: 12.5px;
    }

    label {
        font-size: 14px !important;
        color: white;
    }
</style>




<!--URL -->
<input type="hidden" id="RESOURCE-URL" value="{{url('productos/buscar')}}">
<input type="hidden" id="RESOURCE-URL-ITEM" value="{{url('productos/get')}}">
<input type="hidden" id="PROVEEDOR-URL" value="{{url('proveedores')}}">






@include("buscador.personas", ['TIPO'=>'PROVEEDOR'] )

<h2 class="text-center mt-2" style="font-family: titlefont;">Compras</h2>

<div id="loaderplace"></div>
<div class="row m-0">

    <div class="col-12 col-md-12">
        <form id="COMPRASFORM" action="{{url('compra')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardarCompra(event)">


            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="SUCURSAL" value="<?= $SUCURSAL ?>">
            <input type="hidden" name="REGISTRADO_POR" value="<?= $REGISTRADO_POR ?>">


            <div class="col-12  mb-1">
                <a onclick="event.preventDefault(); if(confirm('Cancelar?')) window.location= this.href; " href="{{url('compra/index')}}" class="btn btn-danger">CANCELAR</a>
                <button type="submit" class="btn btn-warning"> ACEPTAR</button>
            </div>

            <div class="row bg-dark mt-2 pt-1 pb-2 pr-2 pl-2 pr-md-2 pl-md-2">


                <!-- CABECERA  --->

                @include("compra.proceso.header")

                <div class="col-12">
                    @include("compra.proceso.grill.index")
                </div>

            </div>

        </form>

    </div>
</div>


@include("validations.form_validate")
@include("validations.formato_numerico")


<script>
    function limpiar_campos_cabecera() {
        $("input[name=PROVEEDOR]").val("");
        $(".proveedor").val("");
        $("input[name=FECHA]").val("");
        $("input[name=NUMERO]").val("");
    }





    async function guardarCompra(ev) {

        //config_.processData= false; config_.contentType= false;

        ev.preventDefault();
        formValidator.init(ev.target);

        if ($("#COMPRA-DETALLE").children().length == 0) {
            alert("Cargue al menos un item");
            return;
        }
        show_loader();
        //componer
        let cabecera = formValidator.getData("application/json");
        let detalle = compra_model;
        let req = await fetch(ev.target.action, {
            "method": "PUT", 
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
            compraObj.limpiar_tabla();
            alert(resp.ok);
            window.location = "<?= url("compra/index") ?>";
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





    async function crear_proveedor() {
        let url_ = "<?= url('proveedores/create') ?>";
        let req = await fetch(url_, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        let resp = await req.text();

        $("#mymodal .content").html(resp);
        $("#mymodal").removeClass("d-none");
    }









    async function restaurar_modelo_compra() {
        //item cantidad tipo medida
        let row = document.querySelectorAll("#COMPRAS-TABLE tbody tr");
        if (row.length == 0) return;

        let modelo = Array.prototype.map.call(row, function(domtr) {


            let regnro = domtr.id;
            let CODIGO = domtr.children[0].textContent;
            let DESCRIPCION = domtr.children[1].textContent;
            let precio = formValidator.limpiarNumero(domtr.children[3].textContent);
            let MEDIDA = domtr.children[2].textContent;
            let canti = formValidator.limpiarNumero(domtr.children[4].textContent);
            let i5 = formValidator.limpiarNumero(domtr.children[5].textContent);
            let i10 = formValidator.limpiarNumero(domtr.children[6].textContent);
            let TIPO = domtr.className.split("-")[0];

            return {
                CODIGO: CODIGO,
                ITEM: regnro,
                CANTIDAD: canti,
                P_UNITARIO: precio,
                IVA5: i5,
                IVA10: i10,
                TIPO: TIPO
            };

        });
        compra_model = modelo;

    }







    window.onload = function() {


        restaurar_modelo_compra();
        /*Formato numerico **/
        //formato entero

        formatoNumerico.formatearCamposNumericosDecimales("COMPRASFORM");



    };
</script>

@endsection
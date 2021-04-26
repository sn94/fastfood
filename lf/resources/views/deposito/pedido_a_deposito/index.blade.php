@extends("templates.admin.index")


@section("content")



@php
use App\Models\Sucursal;
$SUCURSALES= Sucursal::get();
$SUCURSAL= session("SUCURSAL");


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
<input type="hidden" id="RESOURCE-URL" value="{{url('productos/buscar')}}">
<input type="hidden" id="RESOURCE-URL-ITEM" value="{{url('productos/get')}}">
<input type="hidden" id="PROVEEDOR-URL" value="{{url('proveedores')}}">



<h2 class="text-center mt-2" >Nota de pedido a depósito</h2>





<div id="loaderplace"></div>
<div class="row m-5">
    <div class="col-12 col-md-12">
        <form action="<?= url("deposito/pedido-a-deposito") ?>" method="POST"  onkeypress="if(event.keyCode == 13) event.preventDefault();"    onsubmit="guardar(event)">

            <input type="hidden" name="SUCURSAL" value="<?= session("SUCURSAL") ?>">
            <input type="hidden" name="REGISTRADO_POR" value="<?= session("ID") ?>">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">


            <div class="row bg-dark mt-2 pt-1 pb-2 pr-2 pl-2 pr-md-2 pl-md-2">




                <!-- CABECERA  --->



                <div class="col-12 col-md-3 col-lg-3 mb-1">
                    <div style="display: grid;  grid-template-columns: 20%  80%;">
                        <label style="grid-column-start: 1;" class="mt-1" for="element_7">FECHA: </label>
                        <input value="{{date('Y-m-d')}}" name="FECHA" style="grid-column-start: 2;" class="form-control mt-1" type="date" />
                    </div>
                </div>

              

                <div class="col-12 col-md-5 col-lg-5  mb-1">
                    <div style="display: grid;  grid-template-columns: 20%  80%;">
                        <label style="grid-column-start: 1;" class="mt-1" for="element_7">OBSERVACIÓN: </label>
                        <input name="CONCEPTO" style="grid-column-start: 2;" class="form-control mt-1" type="text" />
                    </div>
                </div>
                <div class="col-12 col-md-2  mb-1">
                    <button type="submit" class="btn btn-danger"> GUARDAR</button>
                </div>


                <!-- 
                     
                end
                 
                CABECERA  --->
            </div>
            @include("deposito.pedido_a_deposito.grill")




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
        if(   $("input[name=FECHA]").val()  == "" ){  alert("Proporcione la fecha"); return; }
        formValidator.init(ev.target);
        if ($("#PEDIDO-DETALLE").children().length == 0) {
            alert("Cargue al menos un item");
            return;
        }
        show_loader();
        //json header 
        let cabecera =   formValidator.getData("application/json");
        let detalle = pedido_model;
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
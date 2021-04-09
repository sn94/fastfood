<?php

use Illuminate\Support\Facades\URL;

$MODULO_FLAG=    isset($_GET['m']) ? $_GET['m'] :  "" ;
$QUERY_FLAG=  $MODULO_FLAG == "c" ? "?m=c"  :  "";
//Plantilla
$templateName = "";
$create = "";
$index = "";

if (  $MODULO_FLAG !=  "c") :
  $templateName = "templates.admin.index";
 

elseif ( $MODULO_FLAG  ==  "c") :
  $templateName = "templates.caja.index";
 
endif;


$create= url("compra$QUERY_FLAG");
$index= url("compra/index$QUERY_FLAG");
?>



@extends( $templateName)



@section("content")


@php


//Datos de compras

$SUCURSAL= isset($COMPRA) ? $COMPRA->SUCURSAL : session("SUCURSAL");
$REGISTRADO_POR= isset($COMPRA) ? $COMPRA->REGISTRADO_POR : session("ID") ;

@endphp

 
<style>
    input:disabled,
    input:disabled {
        background-color: #7d7d7d !important;
    }

    .MEDIDA {
        color: #222;
        font-weight: 600;
    }

    a i.fa-search,
    a i.fa-download,
    a i.fa-plus {
        background-color: #f7fb55;
        border-radius: 30px;
        padding: 3px;
        border: 1px solid black;
        color: black;
    } 

</style>




<!--URL -->
<input type="hidden" id="RESOURCE-URL" value="{{url('productos/buscar')}}">
<input type="hidden" id="RESOURCE-URL-ITEM" value="{{url('productos/get')}}">
<input type="hidden" id="PROVEEDOR-URL" value="{{url('proveedores')}}">

@include("buscador.personas", ['TIPO'=>'PROVEEDOR'] )


<div id="loaderplace"></div>
<div class="container-fluid col-12 col-lg-10 bg-dark text-light">


    <h2 class="text-center mt-2">Compras</h2>

    <form id="COMPRASFORM" action="{{$create}}" method="{{isset($EDICION)?'PUT':'POST'}}" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardarCompra(event)">


        @if( isset( $EDICION) )
        <input type="hidden" name="_method" value="PUT">
        @else
        <input type="hidden" name="_method" value="POST">
        @endif
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="SUCURSAL" value="<?= $SUCURSAL ?>">
        <input type="hidden" name="REGISTRADO_POR" value="<?= $REGISTRADO_POR ?>">


        <div class="col-12  mb-1">
            <a onclick="event.preventDefault(); if(confirm('Cancelar?')) window.location= this.href; " href="{{$index}}" class="btn btn-danger">CANCELAR</a>
            <button type="submit" class="btn btn-warning"> ACEPTAR</button>
        </div>
        <!-- CABECERA  --->
       <div class="row">
       @include("compra.proceso.header")
       </div>

        @include("compra.proceso.grill.index")

    </form>


</div>


@include("validations.form_validate")
@include("validations.formato_numerico")
@include("buscador.Buscador")


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
        let metodo = $("input[name=_method]").val();
        console.log(metodo);
        let req = await fetch(ev.target.action, {

            "method": metodo,

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
            window.location = "<?= $index ?>";
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
        window.myModalCustomHandler = function() {
            if ("ULTIMO_PROVEEDOR" in window && ULTIMO_PROVEEDOR != undefined) {
                $("#PROVEEDOR-KEY").val(ULTIMO_PROVEEDOR.REGNRO);
                $("#PROVEEDOR-NAME").val(ULTIMO_PROVEEDOR.NOMBRES);

                delete window.ULTIMO_PROVEEDOR;
            }

        };
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
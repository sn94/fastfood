@extends("templates.admin.index")


@section("content")

@php
use App\Models\Sucursal;
//FUENTES
$SUCURSALES= Sucursal::get();
$PRODUCCION_ID= isset( $PRODUCCION_ID) ? $PRODUCCION_ID : NULL;
@endphp




@include("salida.estilos")
@include("salida.routes")

<div class="container-fluid col-12 col-md-12 bg-dark text-light mt-2">
<h2 class="text-center mt-2" >Salidas de productos y materia prima</h2>

<div id="loaderplace"></div>

        <form id="SALIDAFORM" action="<?= url("deposito/salida") ?>" method="POST"  onkeypress="if(event.keyCode == 13) event.preventDefault();"    onsubmit="guardarSalida(event)">

            <div class="row">

                <div class="col-12 col-md-6 bg-dark">
                    @include("salida.form.hidden")
                    @include("salida.form.header")
                </div>
                <div class="col-12   col-md-6 bg-dark pt-2">
                    @include("salida.form.grill")
                </div>
            </div>
        </form>
 
</div>


@include("validations.form_validate")
@include("validations.formato_numerico")

<script>
    /**Cargar a la tabla  */







    async function guardarSalida(ev) {
        //config_.processData= false; config_.contentType= false;
         ev.preventDefault();
         
        
        formValidator.init(ev.target);
        if ($("#SALIDA-DETALLE").children().length == 0) {
            alert("Cargue al menos un item");
            return;
        }
        show_loader();
        //componer
        let cabecera = formValidator.getData("application/json");
        let detalle = salida_model;
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
            window.location = "<?= url('deposito/salida') ?>";
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





    async function restaurar_modelo_salida() {
        //item cantidad tipo medida
        let row = document.querySelectorAll("#SALIDA-TABLE tbody tr");
        if (row.length == 0) return;

        let modelo = Array.prototype.map.call(row, function(domtr) {

            let ITEM = domtr.id;
            let CODIGO=  domtr.children[0].textContent;
            let CANTIDAD = domtr.children[3].textContent;
            let MEDIDA = domtr.children[2].textContent;
            let TIPO = domtr.className.split("-")[0];
            return {
                CODIGO:  CODIGO,
                ITEM: ITEM,
                CANTIDAD: CANTIDAD,
                MEDIDA: MEDIDA,
                TIPO: TIPO
            };
        });
        salida_model = modelo;
    }



    window.onload = async function() {
        //   autocompletado_items();
        restaurar_modelo_salida();
        formatoNumerico.formatearCamposNumericosDecimales( "SALIDAFORM");
  

    };
</script>

@endsection
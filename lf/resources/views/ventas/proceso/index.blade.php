@extends("templates.caja.index")


@section("PageTitle")
Nueva venta
@endsection

@section("menu")
@endsection

@section("content")



@include("ventas.proceso.res.impresion")
@include("ventas.proceso.modales.resumen_venta")


 

<div id="loaderplace"></div>


<form class="d-none" action="{{url('ventas')}}" method="POST" id="VENTA-FORM">



    @include("ventas.proceso.modales.formas_pago")

    @csrf
    <input type="hidden" name="FECHA" value="{{date('Y-m-d')}}">
    <input type="hidden" name="SUCURSAL" value="{{session('SUCURSAL')}}">
    <input type="hidden" name="CAJERO" value="{{session('ID')}}">
    <!--HEADER -->


    <!--  CARGA DE TABLA, SELECCION DE MNU -->
    <div class="row   m-0 g-0   p-0">

        <div class="col-12 col-md-6  col-lg-6 p-0 pt-1 m-0 ">
            @include("ventas.proceso.detalle.food_gallery.index" )
        </div>

        <div class="col-12 col-md-6 col-lg-6 p-0 p-md-0  bg-light"  >
            <div class="col-12 pl-0 pr-0 ">

                @include("ventas.proceso.header" )
                @include("ventas.proceso.botons")

            </div>
            <div class="col-12  pl-0 pr-0  ">
                <div id="VENTA_PROCESO_TOTAL" class=" d-flex flex-row bg-light"  >
                    <label class="MONTO fs-4 w-100">TOTAL A PAGAR:</label>
                    <input readonly value="0" type="text" id="TOTAL-VENTA" name="TOTAL" class="entero MONTO form-control" />
                </div>
                @include("ventas.proceso.detalle.grill")
            </div>
        </div>
    </div>

</form>


<!--  END  CARGA DE TABLA, SELECCION DE MNU -->


@include("validations.form_validate")
@include("validations.formato_numerico")



<script>
    var ultimoIdVentaRegistrado = undefined;

    var listaDeProductosJSON = [];



    function show_loader(MENSAJE_ADICIONAL) {

        let mensaje_adicional = MENSAJE_ADICIONAL == undefined ? "" :
            `<div  style='margin: auto;width: 50%; display: flex;flex-direction: column;justify-content: center;'>
            <h3 style='text-align: center;font-family: Arial;color: yellow;' >${MENSAJE_ADICIONAL}</h3>
            <img style='width: 25%;align-self: center;'  src='<?= url("assets/images/loader.gif") ?>'   />
            </div>`;

        let loader = MENSAJE_ADICIONAL == undefined ? "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />" : mensaje_adicional;
        $("#loaderplace").html(loader);
    }

    function hide_loader() {
        $("#loaderplace").html("");
    }



    function initFormVenta() {
        formatoNumerico.formatearCamposNumericosDecimales();
        document.querySelector("input[name=IMPORTE_PAGO]").oninput = function(ev) {
            formatoNumerico.formatearEntero(ev);
            calcularTotalesVuelto(ev);
            actualizarResumenVenta();
        };

        //modal
        $('.modal').on('hidden.bs.modal', function(e) {
            actualizarResumenVenta();
        });
    }



    function nuevaVenta() {
        ultimoIdVentaRegistrado = undefined;
        //limpiar tabla
        $("#grill-ticket").html("");
        //cliente default

        $("#CLIENTE-KEY").val("");
        $("#CLIENTE-NAME").val("");

        //limpiar campos de totales 
        $("#RESUMEN input").val("0");
        $("#TOTAL-VENTA").val("0");
        $("#IMPORTE_PAGO").val("0");
        $("#VUELTO").val("0");
        //limpiar datos de forma de pago
        $("#FORMAS-DE-PAGO input").val("");
        //limpiar ultimo numero de ticket generado
        $("#TICKET-NUMERO").val("");
        $("#TICKET-DATA-PANEL").addClass("d-none");
        ventas_model = [];
    }


    async function imprimirTicket(id_venta) {

        $("#RESUMEN").addClass("d-none");
        let idv = id_venta == undefined ? ultimoIdVentaRegistrado : id_venta;
        if (idv != undefined)
            printDocument.printFromUrl("<?= url("ventas/ticket") ?>/" + idv);
        //   await fetch( "http://localhost:8080/fastfood-print-service/PrinterAssistant");

        nuevaVenta();
    }





    async function guardarVenta() {
        actualizarResumenVenta();

        if ($("#grill-ticket tr").length == 0) {
            alert("Cargue al menos un ítem");
            return;
        }
        formValidator.init(document.getElementById("VENTA-FORM"));
        let payload = formValidator.getData("application/json");

        let formu = document.getElementById("VENTA-FORM");
        show_loader();
        let req = await fetch(formu.action, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({
                    CABECERA: payload,
                    DETALLE: ventas_model
                })
            }

        );
        let resp = await req.json();
        hide_loader();
        if ("ok" in resp) {
            let id_venta = resp.ok;
            ultimoIdVentaRegistrado = id_venta;
            mostrarResumenVenta();
            $("#VENTA-TOTAL-TICKET").val(id_venta);
            $("#TICKET-NUMERO").val(id_venta);
            $("#TICKET-DATA-PANEL").removeClass("d-none");
            //Obtener ticket 
            // imprimirTicket(id_venta);

        } else alert(resp.err);
    }








    async function descargarProductos() {
        let req = await fetch("<?= url('stock/buscar') ?>/VENTA", {
            headers: {
                'formato': "json"
            }
        });
        listaDeProductosJSON = await req.json();
    }








    show_loader("DESCARGANDO DATOS");

    window.onload = function() {

        hide_loader();
        initFormVenta(); //setear formato numerico para campos
        // evento para modal de resumen de pago
        //descargar datos de PRODUCTOS
        descargarProductos();

        $("#VENTA-FORM").removeClass("d-none");
    }
</script>


@endsection

@section("Footer")
@endsection
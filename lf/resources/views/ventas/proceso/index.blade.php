@extends("templates.caja.index")


@section("PageTitle")
Nueva venta
@endsection

 @section("cssStyles")
@include("ventas.proceso.styles")
@endsection 

@section("menu")
@endsection

@section("content")



@include("ventas.proceso.impresion")
@include("ventas.proceso.modales.resumen_venta")


<style>
    body {
        background-color: var(--color-6) !important;
    }
</style>



<div id="loaderplace"></div>


<div class="container-fluid p-0 p-md-2"  >
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
                @include("ventas.proceso.MostradorProductos.index" )
            </div>

            <div class="col-12 col-md-6 col-lg-6 p-0 p-md-0 "  >
                <div class="col-12 pl-0 pr-0 ">

                    @include("ventas.proceso.VentaCabecera.index" )
                    @include("ventas.proceso.VentaCabecera.Botones")

                </div>
                <div class="col-12  pl-0 pr-0 bg-light  ">
                    <div id="VENTA_PROCESO_TOTAL" class=" d-flex flex-row">
                        <label class="MONTO fs-2 w-100">Total a pagar:</label>
                        <input readonly value="0" type="text" id="TOTAL-VENTA" name="TOTAL" class="entero MONTO form-control fs-2 p-0" />
                    </div>
                    @include("ventas.proceso.VentaDetalle.index")
                </div>
            </div>
        </div>

    </form>
</div>


<!--  END  CARGA DE TABLA, SELECCION DE MNU -->


@include("validations.form_validate")
@include("validations.formato_numerico")



<script>
    var ultimoIdVentaRegistrado = undefined;

    var listaDeProductosJSON = [];




    function show_ini_loader(MENSAJE_ADICIONAL) {

        let mensaje_adicional = MENSAJE_ADICIONAL == undefined ? "" :
            `<div id='ini-loader' class="rounded-circle w-50  h-50  d-flex flex-column justify-content-center mx-auto mt-2 pt-5 pb-5"   '>
    <h3 style='text-align: center;color: #b61909;' >${MENSAJE_ADICIONAL}</h3>
    <img style='width: 100px;align-self: center;'  src='<?= url("assets/images/loader.gif") ?>'   />
    </div>`;
        $("body").append(mensaje_adicional);
    }

    function hide_ini_loader() {
        $("#ini-loader").remove();
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
        $("#VENTA-FORM").removeClass("d-none");
    }


    show_ini_loader("DESCARGANDO DATOS");
    
        window.onload = function() {

           hide_ini_loader();
            initFormVenta(); //setear formato numerico para campos
            // evento para modal de resumen de pago
            //descargar datos de PRODUCTOS
            descargarProductos();
        }
</script>


@endsection

@section("Footer")
@endsection
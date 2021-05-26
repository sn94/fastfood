@extends("templates.caja.index")

@section("content")


<?php

use App\Helpers\Utilidades;

?>



<div class="container-fluid bg-dark text-light col-12 col-lg-10  pb-5 mt-2 ">
    <h3 class="text-center">Venta N° {{ $HEADER->REGNRO}}</h3>

    <div class="container bg-light text-dark">
        <div class="row">
            <div class="col col-md-2 d-flex flex-column">
                <label>Fecha:</label>
                <input disabled type="date" value="{{$HEADER->FECHA}}" class="form-control-sm">
            </div>
            <div class="col  d-flex flex-column">
                <label>Origen:</label>

                <input disabled type="text" value="{{ is_null($HEADER->origen_venta) ? '' : $HEADER->origen_venta->DESCRIPCION}}" class="form-control-sm">
            </div>
            <div class="col  d-flex flex-column">
                <label>Cliente CI°:</label>
                <input disabled type="text" value="{{$HEADER->CLIENTE}}" class="form-control-sm">
            </div>
            <div class="col-12 col-md d-flex flex-column">
                <label>Razón Social:</label>
                <input disabled type="text" value="{{$HEADER->cliente->NOMBRE}}" class="form-control-sm">
            </div>
            <div class="col-12 col-md  d-flex flex-column">
                <label>Cajero:</label>
                <input disabled type="text" value="{{$HEADER->cajero->NOMBRES}}" class="form-control-sm">
            </div>
            <div class="col  d-flex flex-column">
                <label>Forma de pago:</label>
                <input disabled type="text" value="{{$HEADER->FORMA}}" class="form-control-sm">
            </div>
            <!--TARJETA-->
            <fieldset class="border border-secondary pb-1">
                <legend class="fs-6 bg-secondary  text-light text-center   ">Tarjeta</legend>
                <div class="row">
                    <div class="col  d-flex flex-column">
                        <label>Cuenta:</label>
                        <input disabled type="text" value="{{$HEADER->TAR_CUENTA}}" class="form-control-sm">
                    </div>
                    <div class="col  d-flex flex-column">
                        <label>Banco:</label>
                        <input disabled type="text" value="{{$HEADER->TAR_BANCO}}" class="form-control-sm">
                    </div>

                    <div class="col  d-flex flex-column">
                        <label>Cédula N°:</label>
                        <input disabled type="text" value="{{$HEADER->TAR_CEDULA}}" class="form-control-sm">
                    </div>
                    <div class="col  d-flex flex-column">
                        <label>Boleta N°:</label>
                        <input disabled type="text" value="{{$HEADER->TAR_BOLETA}}" class="form-control-sm">
                    </div>
                </div>
            </fieldset>
            <!--Giro-->
            <fieldset class="border border-secondary pb-1">
                <legend class="fs-6 bg-secondary  text-light text-center   ">Giros</legend>
                <div class="row">
                    <div class="col  d-flex flex-column">
                        <label>N° Teléfono:</label>
                        <input disabled type="text" value="{{$HEADER->GIRO_TELEFONO}}" class="form-control-sm">
                    </div>
                    <div class="col  d-flex flex-column">
                        <label>Cédula N°:</label>
                        <input disabled type="text" value="{{$HEADER->GIRO_CEDULA}}" class="form-control-sm">
                    </div>
                    <div class="col  d-flex flex-column">
                        <label>Titular:</label>
                        <input disabled type="text" value="{{$HEADER->GIRO_TITULAR}}" class="form-control-sm">
                    </div>
                    <div class="col  d-flex flex-column">
                        <label>Fecha:</label>
                        <input disabled type="text" value="{{$HEADER->GIRO_FECHA}}" class="form-control-sm">
                    </div>
                </div>
            </fieldset>


        </div>
    </div>

    <div class="container bg-light" id="grill">

        <table id="VENTATABLE" class="table table-stripped table-light">
            <thead class="thead-dark">
                <tr>
                    <th class="p-0">Cantidad</th>
                    <th class="p-0">Descripción</th>
                    <th class="p-0 text-end">Precio</th>
                    <!--  <th>Exe.</th>-->
                    <th class="p-0 text-end">Subtotal</th>

                </tr>
            </thead>
            <tbody id="grill-ticket" style="font-weight: 600; color: black;">

                @php
                $TOTAL= 0;
                @endphp
                @foreach( $DETALLE as $detalle)

                <tr>
                    <td class="p-0">{{$detalle->CANTIDAD}}</td>
                    <td class="p-0">{{$detalle->producto->DESCRIPCION}}</td>
                    <td class="p-0 text-end">{{ Utilidades::number_f( $detalle->P_UNITARIO) }}</td>
                    <td class="p-0 text-end">{{ Utilidades::number_f($detalle->P_UNITARIO * $detalle->CANTIDAD) }}</td>
                </tr>

                @php
                $TOTAL+= $detalle->P_UNITARIO * $detalle->CANTIDAD ;
                @endphp
                @endforeach

            </tbody>

            <tfoot>
                <tr>
                    <td colspan="3" class="p-0"></td>
                    <td class="text-end p-0"> {{ Utilidades::number_f( $TOTAL )}} </td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>

<script>
    async function fill_grill() {


        let grill_url = "<?= url('ventas/index') ?>";


        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#grill").html(loader);
        let req = await fetch(grill_url, {

            headers: {
                'X-Requested-With': "XMLHttpRequest"
            }
        });
        let resp = await req.text();
        $("#grill").html(resp);

    }
</script>


@endsection
@php


$PRODUCCION_ID= isset( $PRODUCCION_ID) ? $PRODUCCION_ID : ( isset($SALIDA) ? $SALIDA->PRODUCCION_ID : NULL);
$FECHA= isset( $SALIDA) ? $SALIDA->FECHA : date( 'Y-m-d');
$TIPO_SALIDA= isset( $SALIDA) ? $SALIDA->TIPO_SALIDA : '';
$DESTINO= isset( $SALIDA) ? $SALIDA->DESTINO : '';
$SUCURSAL_DESTINO= isset( $SALIDA) ? $SALIDA->SUCURSAL_DESTINO : '';
$CONCEPTO= isset( $SALIDA) ? $SALIDA->CONCEPTO : '';

@endphp


@if(  isset($SALIDA))

<input type="hidden" name="REGNRO" value="{{$SALIDA->REGNRO}}">
@endif

<input type="hidden" name="REGISTRADO_POR" value="<?= session("ID") ?>">
<input type="hidden" name="SUCURSAL" value="<?= session("SUCURSAL") ?>">
<input type="hidden" name="PRODUCCION_ID" value="{{$PRODUCCION_ID}}">
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">



<div class="row   pt-1  pr-0 pl-0">



    <!-- CABECERA  --->


    <div class="col-12   col-sm-6 col-md-6  mb-1">

        <label class="mt-1 fs-6" for="element_7">Orden de Producción: </label>

        <div class="d-flex flex-row">
        <a href="#" onclick="buscarFichaProduccion()"><i class="fa fa-search"></i></a>
            <input value="{{$PRODUCCION_ID}}" readonly name="PRODUCCION_ID" id="PRODUCCION_ID" class="form-control mt-1 fs-6" type="text" maxlength="15" />
           
        </div>


        <label style="grid-column-start: 1;" class="mt-1 mr-0 pr-0 fs-6" for="element_7">Fecha: </label>
        <input value="{{$FECHA}}" name="FECHA" style="grid-column-start: 2;" class=" ml-0 form-control mt-1" type="date" />

@if( false)

        <label style="grid-column-start: 1;" class="mt-1 fs-6" for="element_7">Salida: </label>
        <x-tipo-stock-chooser id="" name="TIPO_SALIDA" :value="$TIPO_SALIDA" callback="cambiar_tipo_salida(event);" class="form-control" />
@endif

    </div>



    <div class="col-12 col-sm-6 col-md-6   ">
        <label style="grid-column-start: 1;" class="mt-1 fs-6" for="element_7">Destino: </label>
        @php
        $tipos_destino= [ "COCINA" => "COCINA", "SUCURSAL"=> "SUCURSAL" ];
        @endphp
        <select class="form-control" name="DESTINO" onchange="cambiar_destino(event)">
            @foreach( $tipos_destino as $tkey=> $tval)
            @if( $tkey == $DESTINO)
            <option selected value="{{$tkey}}"> {{$tval}}</option>
            @else
            <option value="{{$tkey}}"> {{$tval}}</option>
            @endif
            @endforeach
        </select>

        <div id="SUCURSAL_DESTINO" class="d-none">
            <label style="grid-column-start: 1;" class="fs-6">SUCURSAL: </label>
            <select style="grid-column-start: 2;" class="form-control" name="SUCURSAL_DESTINO">
                @foreach( $SUCURSALES as $sucursal)
                @if( $tkey == $SUCURSAL_DESTINO)
                <option selected value="{{$sucursal->REGNRO}}">{{$sucursal->DESCRIPCION}}</option>
                @else
                <option value="{{$sucursal->REGNRO}}">{{$sucursal->DESCRIPCION}}</option>
                @endif

                @endforeach
            </select>
        </div>

        <label style="grid-column-start: 1;" class="mt-1 fs-6 " for="element_7">Observación: </label>
        <input value="{{$CONCEPTO}}" name="CONCEPTO" style="grid-column-start: 2;" class="form-control mt-1" type="text" />


    </div>





    <!--     end      CABECERA  --->
</div>


<script>
    function cambiar_tipo_salida(ev) {

        let tipo_Sel = ev.target.value;

        switch (tipo_Sel) {
            case "MP":
                $("select[name=DESTINO]").val("COCINA");
                $("#SUCURSAL_DESTINO").addClass("d-none");
                break;
            case "PP":
                ;
                break;
            case "PE":
                $("select[name=DESTINO]").val("SUCURSAL");
                $("#SUCURSAL_DESTINO").removeClass("d-none");
                break;
            default:
                ;
                break;
        }
        $("#SALIDA-DETALLE").html("");
        salida_model = [];
    }





    function cambiar_destino(ev) {
        let tipo_Sel = ev.target.value;
        if (tipo_Sel == "SUCURSAL")
            $("#SUCURSAL_DESTINO").removeClass("d-none");
        else
            $("#SUCURSAL_DESTINO").addClass("d-none", true);

    }
</script>
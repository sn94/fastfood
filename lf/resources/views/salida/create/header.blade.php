<input type="hidden" name="REGISTRADO_POR" value="<?= session("ID") ?>">
<input type="hidden" name="SUCURSAL" value="<?= session("SUCURSAL") ?>">
<input type="hidden" name="PRODUCCION_ID" value="{{$PRODUCCION_ID}}">
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">



<div class="row   pt-1  pr-0 pl-0">



    <!-- CABECERA  --->


    <div class="col-12   col-sm-6 col-md-6  mb-1">
        @if( ! is_null($PRODUCCION_ID) )
        <label class="mt-1" for="element_7">ORDEN DE PRODUCCIÓN: </label>
        <input readonly value="{{$PRODUCCION_ID}}" name="PRODUCCION_ID" class="form-control mt-1" type="text" />
        @endif
        <label style="grid-column-start: 1;" class="mt-1 mr-0 pr-0" for="element_7">FECHA: </label>
        <input value="{{date('Y-m-d')}}" name="FECHA" style="grid-column-start: 2;" class=" ml-0 form-control mt-1" type="date" />


        <label style="grid-column-start: 1;" class="mt-1" for="element_7">SALIDA: </label>
        @php
        $tipos_item= [ "MP" => "MATERIA PRIMA", "PP"=> "PARA VENTA", "PE"=>"PRODUCTO ELABORADO", "AF"=> "MOBILIARIO Y OTROS" ];
        @endphp
        <select class="form-control" name="TIPO_SALIDA" onchange="cambiar_tipo_salida(event);  ">
            @foreach( $tipos_item as $tkey=> $tval)
            @if( $tkey == "MP")
            <option selected value="{{$tkey}}"> {{$tval}}</option>
            @else
            <option value="{{$tkey}}"> {{$tval}}</option>
            @endif
            @endforeach
        </select>

    </div>



    <div class="col-12 col-sm-6 col-md-6   ">
        <label style="grid-column-start: 1;" class="mt-1" for="element_7">DESTINO: </label>
        @php
        $tipos_destino= [ "COCINA" => "COCINA", "SUCURSAL"=> "SUCURSAL" ];
        @endphp
        <select class="form-control" name="DESTINO" onchange="cambiar_destino(event)">
            @foreach( $tipos_destino as $tkey=> $tval)
            @if( $tkey == "MP")
            <option selected value="{{$tkey}}"> {{$tval}}</option>
            @else
            <option value="{{$tkey}}"> {{$tval}}</option>
            @endif
            @endforeach
        </select>

       <div id="SUCURSAL_DESTINO" class="d-none" >
       <label style="grid-column-start: 1;">SUCURSAL: </label>
        <select style="grid-column-start: 2;" class="form-control" name="SUCURSAL_DESTINO">
            @foreach( $SUCURSALES as $sucursal)
            <option value="{{$sucursal->REGNRO}}">{{$sucursal->DESCRIPCION}}</option>
            @endforeach
        </select>
       </div>

        <label style="grid-column-start: 1;" class="mt-1 " for="element_7">OBS.: </label>
        <input name="CONCEPTO" style="grid-column-start: 2;" class="form-control mt-1" type="text" />


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
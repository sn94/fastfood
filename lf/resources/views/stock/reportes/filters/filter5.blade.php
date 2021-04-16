<div class="row ">

    <div class="col  col-lg-2  pb-0">
        <label> Desde: </label>
        <input class="form-control form-control-sm" type="date" id="FECHA_DESDE" onchange="filtrar()">
    </div>

    <div class="col col-lg-2  pb-0">
        <label> Hasta:</label>
        <input class="form-control form-control-sm" type="date" id="FECHA_HASTA" onchange="filtrar()">
    </div>

    <div class="col col-lg-2  pb-0">
        <label>
            Sucursal:
        </label>
        <x-sucursal-chooser class="form-control-sm" name="SUCURSAL" value="" id="SUCURSAL" callback="filtrar()" style=""></x-sucursal-chooser>
        <x-pretty-checkbox callback="filtrar()" id="F_SUCURSAL" name="" value="N" onValue="S" offValue="N" label=""></x-pretty-checkbox>
    </div>

    <div class="col col-lg-2 pb-0 d-flex flex-column">
        <label>
            Destino:
        </label>
        @php
        $tipos_destino= [ "COCINA" => "COCINA", "SUCURSAL"=> "SUCURSAL" ];
        @endphp
        <select id="F_DESTINO" onchange="filtrar()" class="form-control-sm">
            @foreach( $tipos_destino as $tkey=> $tval)
            <option value="{{$tkey}}"> {{$tval}}</option>

            @endforeach
        </select>
    </div>


    <div class="col col-lg-1 pb-0  d-flex  align-items-center">
        <button onclick="filtrar()" class="btn btn-warning btn-sm">Buscar</button>
    </div>
</div>

<script>
    //Los ingredientes mas utilizados en cocina
    async function filtrar(ev) {

        let page_index = 1;

        //prevenir propagacion
        if (ev != undefined && typeof ev == "object") {
            ev.preventDefault();
            let url_parts = ev.target.href.split("?");
            if (url_parts.length > 1) page_index = url_parts[1].split("=")[1];
        }

        let fecha_desde = $("#FECHA_DESDE").val();
        let fecha_hasta = $("#FECHA_HASTA").val();
        let sucursal = $("#SUCURSAL").val();
let destino=  $("#F_DESTINO").val();

        let param = {
            FILTRO: 5,
            FECHA_DESDE: fecha_desde,
            FECHA_HASTA: fecha_hasta,
            DESTINO: destino,
            page: page_index
        };

        if ($("#F_SUCURSAL").val() == "S")
            param.SUCURSAL = sucursal;
        buscarStock(param);
    }
</script>
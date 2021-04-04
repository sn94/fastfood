<div class="row ">

    <div class="col 2 pb-0">
        <label> Desde: </label>
        <input class="form-control form-control-sm" type="date" id="FECHA_DESDE" onchange="filtrar()">
    </div>

    <div class="col 2 pb-0">
        <label> Hasta:</label>
        <input class="form-control form-control-sm" type="date" id="FECHA_HASTA" onchange="filtrar()">
    </div>
    <div class="col  pb-0  d-flex  align-items-end">
    <button onclick="filtrar()" class="btn btn-warning btn-sm">Buscar</button>
    </div>
</div>

<script>
    async function filtrar() {
        let fecha_desde = $("#FECHA_DESDE").val();
        let fecha_hasta = $("#FECHA_HASTA").val();

        let param = {
            FILTRO: 2,
            FECHA_DESDE: fecha_desde,
            FECHA_HASTA: fecha_hasta
        };

       
        buscarCompras(param);

    }
</script>
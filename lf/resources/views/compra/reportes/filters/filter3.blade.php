<div class="row ">

  <div class="col pb-0">
    <label>
      Sucursal:
    </label>
    <x-sucursal-chooser class="form-control form-control-sm" name="SUCURSAL" value="" id="SUCURSAL" callback="filtrar()" style=""></x-sucursal-chooser>
  </div>

  <div class="col pb-0">
    <label> Desde: </label>
    <input class="form-control form-control-sm" type="date" id="FECHA_DESDE" onchange="filtrar()">
  </div>

  <div class="col pb-0">
    <label> Hasta:</label>
    <input class="form-control form-control-sm" type="date" id="FECHA_HASTA" onchange="filtrar()">
  </div>
</div>


<script>
  async function filtrar() {
    let sucursal = $("#SUCURSAL").val();
    let fecha_desde = $("#FECHA_DESDE").val();
    let fecha_hasta = $("#FECHA_HASTA").val();

    let param = {
      FILTRO: 3
    };

    if (sucursal != "") param.SUCURSAL = sucursal;
    if (fecha_desde != "" && fecha_hasta != "") {
      param.FECHA_DESDE = fecha_desde;
      param.FECHA_HASTA = fecha_hasta;
    }

    buscarCompras(param);

  }
</script>
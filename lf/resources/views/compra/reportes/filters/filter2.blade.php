<div class="row ">

    <div class="col  pb-0">
        <label> Desde: </label>
        <input class="form-control form-control-sm" type="date" id="FECHA_DESDE" onchange="filtrar()">
    </div>

    <div class="col  pb-0">
        <label> Hasta:</label>
        <input class="form-control form-control-sm" type="date" id="FECHA_HASTA" onchange="filtrar()">
    </div>

    <div class="col  pb-0">
    <label>
      Sucursal:
    </label>
    <x-sucursal-chooser class="form-control form-control-sm" name="SUCURSAL" value="" id="SUCURSAL" callback="filtrar()" style=""></x-sucursal-chooser>
    <x-pretty-checkbox callback="filtrar()" id="F_SUCURSAL" name=""  value="N" onValue="S"  offValue="N"  label=""></x-pretty-checkbox>
  </div>

  <div class="col  pb-0">
    <label>
      Por:
    </label>
    <select  id="MAS_COMPRADO" class="form-control form-control-sm" onchange="filtrar()">
    <option value="COMPRAS">NÚMERO DE COMPRAS</option>
    <option value="CANTIDAD">CANTIDAD</option>
    </select> 
    <x-pretty-checkbox callback="filtrar()" id="F_MAS_COMPRADO" name=""  value="N" onValue="S"  offValue="N"  label=""></x-pretty-checkbox>
  </div>


    <div class="col  pb-0  d-flex  align-items-center">
    <button onclick="filtrar()" class="btn fast-food-form-button btn-sm">Buscar</button>
    </div>
</div>

<script>
    async function filtrar() {
        let fecha_desde = $("#FECHA_DESDE").val();
        let fecha_hasta = $("#FECHA_HASTA").val();
        let sucursal = $("#SUCURSAL").val();
        let mas_comprado = $("#MAS_COMPRADO").val();

        let param = {
            FILTRO: 2,
            FECHA_DESDE: fecha_desde,
            FECHA_HASTA: fecha_hasta
        };

        if($("#F_SUCURSAL").val() ==  "S")
        param.SUCURSAL=  sucursal;
        if($("#F_MAS_COMPRADO").val() ==  "S")
        param.MAS_COMPRADO=  mas_comprado;
       
        buscarCompras(param);

    }
</script>
<div class="row pt-1">


<div class="col-12 col-sm-4 col-md-3 col-lg-2  pb-0">
        <label> Desde: </label>
        <input class="form-control form-control-sm" type="date" id="FECHA_DESDE" onchange="filtrar()">
    </div>

    <div class="col-12 col-sm-4  col-md-3 col-lg-2 pb-0">
        <label> Hasta:</label>
        <input class="form-control form-control-sm" type="date" id="FECHA_HASTA" onchange="filtrar()">
    </div>



    <div class="col-12  col-sm-4 col-md-3 col-lg-2 pb-0">
        <label>Tipo:</label>
        <x-tipo-stock-chooser id="TIPO-STOCK" value="" style="width:100% !important;" callback="filtrar()" class="form-control form-control-sm"></x-tipo-stock-chooser>
    <x-pretty-checkbox callback="filtrar()"  id="F_TIPO_STOCK" name=""  value="N" onValue="S"  offValue="N"  label=""></x-pretty-checkbox>
    </div>

    

    <div class="col-12 col-sm-4   col-md-3 col-lg-2 pb-0">
    <label>
      Sucursal:
    </label>
    <x-sucursal-chooser class="form-control form-control-sm" name="SUCURSAL" value="" id="SUCURSAL" callback="filtrar()" style=""></x-sucursal-chooser>
    <x-pretty-checkbox callback="filtrar()" id="F_SUCURSAL" name=""  value="N" onValue="S"  offValue="N"  label=""></x-pretty-checkbox>
  </div>


    <div class="col  pb-0  d-flex  align-items-center">
    <button onclick="filtrar()" class="btn btn-warning btn-sm">Buscar</button>
    </div>
</div>


<script>
    async function filtrar() {
        let fecha_desde = $("#FECHA_DESDE").val();
        let fecha_hasta = $("#FECHA_HASTA").val();
        let tipo_stock = $("#TIPO-STOCK").val(); 
        let sucursal = $("#SUCURSAL").val(); 

        let param = {
            FILTRO: 1
        };

        if(  fecha_desde != "" &&  fecha_hasta != ""){
            param.FECHA_DESDE= fecha_desde;
            param.FECHA_HASTA= fecha_hasta;
        }
        if($("#F_TIPO_STOCK").val() ==  "S")
        param.TIPO_STOCK=  tipo_stock;
      
        if($("#F_SUCURSAL").val() ==  "S")
        param.SUCURSAL=  sucursal;
    
        buscarStock(param);

    }
</script>
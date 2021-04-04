<div class="row pt-1">
    <div class="col col-md-5  pb-0 d-flex flex-row">
        <label>Tipo:</label>
        <x-tipo-stock-chooser id="TIPO-STOCK" value="" style="width:100% !important;" callback="filtrar()" class="form-control form-control-sm"></x-tipo-stock-chooser>
    <x-pretty-checkbox id="F_TIPO_STOCK" name=""  value="S" onValue="S"  offValue="N"  label=""></x-pretty-checkbox>
    </div>

    <div class="col  col-md-5 pb-0   d-flex flex-row">
        <label>
            Proveedor:
        </label>
        <x-proveedor-chooser callback="filtrar()" class="form-control form-control-sm" style="" name="" value="" id="PROVEEDOR"></x-proveedor-chooser>
        <x-pretty-checkbox id="F_PROVEEDOR" name=""  value="S" onValue="S"  offValue="N"  label=""></x-pretty-checkbox>
    </div>
    <div class="col  pb-0  d-flex  align-items-end">
    <button onclick="filtrar()" class="btn btn-warning btn-sm">Buscar</button>
    </div>
</div>


<script>
    async function filtrar() {
        let tipo_stock = $("#TIPO-STOCK").val();
        let proveedor = $("#PROVEEDOR").val();
        let param = {
            FILTRO: 1
        };

        if($("#F_TIPO_STOCK").val() ==  "S")
        param.TIPO_STOCK=  tipo_stock;
        if($("#F_PROVEEDOR").val() ==  "S")
        param.PROVEEDOR=  proveedor;
        buscarCompras(param);

    }
</script>
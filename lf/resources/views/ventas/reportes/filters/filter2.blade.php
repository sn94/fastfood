@include("buscador.Buscador")
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
        <label> Forma de pago:</label>
        <x-forma-pago-chooser   id="FORMA_PAGO" name="" value="" style="" class="form-control form-control-sm"   callback="filtrar()" ></x-forma-pago-chooser>
        <x-pretty-checkbox callback="filtrar()" id="F_FORMA_PAGO" name=""  value="N" onValue="S"  offValue="N"  label=""></x-pretty-checkbox>
      
    </div>

    <div class="col  pb-0">
        <label> Ventas anuladas:</label>
        <x-pretty-checkbox callback="filtrar()" id="F_ANULADAS" name=""  value="A" onValue="B"  offValue="A"  label=""></x-pretty-checkbox>
      
    </div>
    
    <div class="col  pb-0">
        <label> Cajero/a:</label>
        <div class="d-flex flex-row ">
        <a  href="#" onclick="abrirBuscadorCajero()"><i class="fa fa-search"></i></a>
<input type="hidden"  id="CAJERO">
<input type="text" readonly id="CAJERO-NOMBRE" class="form-control-sm" >
        </div>
        <x-pretty-checkbox callback="filtrar()" id="F_CAJERO" name=""  value="N" onValue="S"  offValue="N"  label=""></x-pretty-checkbox>
      
    </div>
    


    <div class="col  pb-0">
    <label>
      Sucursal:
    </label>
    <x-sucursal-chooser class="form-control form-control-sm" name="SUCURSAL" value="" id="SUCURSAL" callback="filtrar()" style=""></x-sucursal-chooser>
    <x-pretty-checkbox callback="filtrar()" id="F_SUCURSAL" name=""  value="N" onValue="S"  offValue="N"  label=""></x-pretty-checkbox>
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
        let forma_pago = $("#FORMA_PAGO").val();
        let anuladas = $("#F_ANULADAS").val();
        let cajero = $("#CAJERO").val();
       
        let param = {
            FILTRO: 2,
            ESTADO: anuladas
        };

        if( fecha_desde != "")  param.FECHA_DESDE=  fecha_desde;
        if( fecha_hasta != "")  param.FECHA_HASTA=  fecha_hasta;
        if($("#F_SUCURSAL").val() ==  "S")
        param.SUCURSAL=  sucursal;
        if($("#F_FORMA_PAGO").val() ==  "S")
        param.FORMA_PAGO=  forma_pago;
        if($("#F_CAJERO").val() ==  "S")
        param.CAJERO=  cajero;
        
       
        buscarVentas(param);

    }


    function  abrirBuscadorCajero(){
    //KEY:'#PROVEEDOR-KEY',NAME:'#PROVEEDOR-NAME


    Buscador.url= "<?=url("usuario")?>";
    Buscador.htmlFormForParams= "<input type='hidden' value='CAJA' /> ";
    Buscador.columnNames= ["REGNRO", "CEDULA", "NOMBRES"];
    Buscador.columnLabels= ['ID', 'CÉDULA', 'NOMBRES'];
    Buscador.callback= function(   seleccionado){

        $('#CAJERO').val( seleccionado.REGNRO);
        $('#CAJERO-NOMBRE').val( seleccionado.NOMBRES);
        filtrar();
    };
    Buscador.render();
}



</script>
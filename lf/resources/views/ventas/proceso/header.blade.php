@include("buscador.Buscador")
<style>
   

    #VENTA-HEADER-SESION {
        display: grid;
        grid-template-columns: 40% 10% 40% 10%;
        background: #ab0000;
        margin: 0px;
        padding-top: 2px;
        align-items: baseline !important;
    }


    #VENTA-HEADER-SESION label {
        font-size: 1.3em !important; 
        text-align: center;
    }

    #VENTA-HEADER-SESION input {
        font-size: 1.3em !important;
        padding: 0px !important;
        border-bottom: 1px solid white !important;
        text-align: center;
    }




   

    #VENTA-HEADER-FORMA {
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
    }


  

    #CLIENTE::placeholder {
        color: #000 !important;
        font-weight: 600;
    }


    /* Elemento | http://127.0.0.1:8000/ventas */

    #CLIENTES-RESULTADOS {
        height: 200px;
        background: #fee743;

        z-index: 1000000000000000000;
        position: absolute;
        top: 38px;
        width: 100%;
        left: 30%;
        overflow: auto;
    }

    #CLIENTES-RESULTADOS a {
        color: black;
    }

  

    a i.fa-edit,
    a i.fa-plus,
    a i.fa-search {
        background-color: #ff0000;
        border-radius: 40px;
        padding: 5px;
        border: 1px solid black;
        color: white;
    }
</style>


<!-- DATOS CLIENTE MODO DE PAGO -->



<div id="VENTA-HEADER" class="row bg-dark   m-0 m-md-0   mb-lg-0">

    <div id="VENTA-HEADER-SESION" class="col-12 p-1  ">
 
        <label class="text-light pr-1">SESIÓN N°:</label>
        <input readonly value="{{session('SESION')}}" class="form-control pl-1" type="text">
    </div>



    <div id="VENTA-HEADER-CLIENTE" class="col-12  bg-light   ">
        <div class="row text-dark p-0 g-0">
             
            <div class="col-3">
            <a href="#" onclick="crear_cliente()"><i class="fa fa-plus"></i></a>
            <label class="pr-1 pl-1 fs-4">Cliente:</label>
            </div>
            <div class="col-2">
            <input readonly id="CLIENTE-KEY"   class="fs-5 form-control form-control-md pl-1 bg-secondary" type="text" name="CLIENTE">
            </div>
            <div class="col-6">
            <input readonly id="CLIENTE-NAME" class="fs-5 form-control bg-secondary" type="text" />
            </div>
            <div class="col-1 d-flex align-items-center">
            <a href="#" onclick="abrirBuscadorCliente()"><i class="fa fa-search"></i></a>
            </div>

        </div>
     
     
        
    </div>







</div>
<!--  END DATOS CLIENTE MODO DE PAGO-->


<script>
    async function crear_cliente() {
        let url_ = "<?= url('clientes/create') ?>";
        let req = await fetch(url_, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        let resp = await req.text();

        window.myModalCustomHandler = function() {
            if ("ULTIMO_CLIENTE" in window &&  ULTIMO_CLIENTE != undefined) {
                $("#CLIENTE-KEY").val(ULTIMO_CLIENTE.REGNRO);
                $("#CLIENTE-NAME").val(ULTIMO_CLIENTE.NOMBRES);
               
                delete window.ULTIMO_CLIENTE;
            }
        };

        $("#mymodal .content").html(resp);
        $("#mymodal").removeClass("d-none");
    }



    function  abrirBuscadorCliente(){
    //KEY:'#PROVEEDOR-KEY',NAME:'#PROVEEDOR-NAME


    Buscador.url= "<?=url("clientes")?>";
    Buscador.columnNames= ["REGNRO", "CEDULA_RUC", "NOMBRE"];
    Buscador.columnLabels= ['ID', 'CÉDULA', 'NOMBRES'];
    Buscador.callback= function(   seleccionado){

        $('#CLIENTE-KEY').val( seleccionado.REGNRO);
        $('#CLIENTE-NAME').val( seleccionado.NOMBRE);
    };
    Buscador.render();
}



    function cambiarFormaDePago(esto) {


        if (esto.value != "EFECTIVO")
            $("#FORMAS-DE-PAGO").modal("show");
    }
</script>
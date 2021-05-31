<div class="row text-dark p-0 g-0">

    <div class="col-3">
        <a href="#" onclick="crear_cliente()"><i class="fa fa-plus"></i></a>
        <label class="pr-1 pl-1 fs-5 text-light">Cliente:</label>
    </div>
    <div class="col-2">
        <input readonly id="CLIENTE-KEY" class="fs-5 form-control p-0 pl-1 bg-secondary text-center" type="text" name="CLIENTE">
    </div>
    <div class="col-6">
        <input readonly id="CLIENTE-NAME" class="fs-5 form-control p-0  bg-secondary" type="text" />
    </div>
    <div class="col-1 d-flex align-items-center">
        <a href="#" onclick="abrirBuscadorCliente()"><i class="fa fa-search"></i></a>
    </div>

</div>


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
            if ("ULTIMO_CLIENTE" in window && ULTIMO_CLIENTE != undefined) {
                $("#CLIENTE-KEY").val(ULTIMO_CLIENTE.REGNRO);
                $("#CLIENTE-NAME").val(ULTIMO_CLIENTE.NOMBRES);

                delete window.ULTIMO_CLIENTE;
            }
        };

        $("#mymodal .content").html(resp);
        $("#mymodal").removeClass("d-none");
    }



    function abrirBuscadorCliente() {
        //KEY:'#PROVEEDOR-KEY',NAME:'#PROVEEDOR-NAME


        Buscador.url = "<?= url("clientes") ?>";
        Buscador.columnNames = ["REGNRO", "CEDULA_RUC", "NOMBRE"];
        Buscador.columnLabels = ['ID', 'CÉDULA', 'NOMBRES'];
        Buscador.callback = function(seleccionado) {

            $('#CLIENTE-KEY').val(seleccionado.REGNRO);
            $('#CLIENTE-NAME').val(seleccionado.NOMBRE);
        };
        Buscador.render();
    }
</script>
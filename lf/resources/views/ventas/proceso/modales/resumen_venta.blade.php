<style>
    #RESUMEN {
        z-index: 100000000;
        position: absolute;
        width: 100%;
        height: 100%;
    }

    #RESUMEN div.container {

        display: flex;
        flex-direction: column;
        background: #fae9e8;
        border: #ff5131 4px solid;
    }



    #RESUMEN label {
        color: black;
        font-family: titlefont;
        font-weight: 600;
        text-align: center;
        font-size: 14px;
        width: 100% !important;
    }



    #RESUMEN input {
        background-color: #fae9e8;
        border: none;
        text-align: right;
        font-size: 14px;
        font-family: titlefont;
        width: 100%;
    }

    #RESUMEN button.cierra {
        background-color: black;
        color: yellow;
    }


    #RESUMEN-PAGO-TARJETA,
    #RESUMEN-PAGO-GIRO {

        display: flex;
        flex-direction: row;
    }

    #RESUMEN-PAGO-TARJETA label,
    #RESUMEN-PAGO-TARJETA input,
    #RESUMEN-PAGO-GIRO label,
    #RESUMEN-PAGO-GIRO input,
    #RESUMEN-FORMAPAGO label,
    #RESUMEN-FORMAPAGO input {
        font-size: 11.5px !important;
        height: 25px;
        font-family: Arial, Helvetica, sans-serif;
    }
</style>

<div id="RESUMEN" class="container-fluid d-none ">


    <div class="container col-12 col-md-4  ">

        <div>
            <button onclick="$('#RESUMEN').addClass('d-none');   nuevaVenta();" class="cierra" type="button">X</button>
        </div>


      
        <div style="display: flex;flex-direction: row;">
            <label for="">TOTAL:</label>
            <input readonly value="0" type="text" id="VENTA-TOTAL-RESUMEN">
        </div>
        <div style="display: flex;flex-direction: row;">
            <label for="">TICKET N°:</label>
            <input readonly value="0" type="text" id="VENTA-TOTAL-TICKET">
        </div>
        <div style="display: flex;flex-direction: row;">
            <label for="">ENTREGA:</label>
            <input readonly value="0" type="text" id="VENTA-ENTREGA-RESUMEN">
        </div>
        <div style="display: flex;flex-direction: row;">
            <label for="">VUELTO:</label>
            <input readonly value="0" type="text" id="VENTA-VUELTO-RESUMEN">
        </div>
        <div id="RESUMEN-FORMAPAGO" style="border: 1px solid black;display: flex;flex-direction: row;">

            <label for="">FORMA DE PAGO:</label>
            <input readonly type="text" id="VENTA-FORMA-PAGO">
        </div>

        <!--- tarjeta -->
        <div id="RESUMEN-PAGO-TARJETA" class="d-none" style="border: 1px solid black;">

            <table>
                <tr>
                    <td class="p-0"> <label for="">N° CUENTA:</label></td>
                    <td class="p-0"> <input readonly type="text" id="TAR_CUENTA"></td>
                    <td class="p-0"> <label for="">BANCO:</label></td>
                    <td class="p-0"> <input readonly type="text" id="TAR_BANCO"> </td>
                </tr>
                <tr>
                    <td class="p-0"> <label for="">CÉDULA:</label></td>
                    <td class="p-0"> <input readonly type="text" id="TAR_CEDULA"> </td>
                    <td class="p-0"> <label for="">BOLETA N°:</label></td>
                    <td class="p-0"> <input readonly type="text" id="TAR_BOLETA"></td>
                </tr>
            </table>

        </div>
        <!--- giro -->
        <div id="RESUMEN-PAGO-GIRO" class="d-none" style="border: 1px solid black; ">

            <div style="display: flex;flex-direction: column;">
                <label for="">N° TELÉFONO:</label>
                <input readonly type="text" id="GIRO_TELEFONO">
                <label for="">TITULAR:</label>
                <input readonly type="text" id="GIRO_TITULAR">
            </div>
            <div style="display: flex;flex-direction: column;">
                <label for="">CÉDULA:</label>
                <input readonly type="text" id="GIRO_CEDULA">
                <label for="">FECHA/HORA DE TRANS.:</label>
                <input readonly type="text" id="GIRO_FECHA">
            </div>
        </div>
        <button onclick="imprimirTicket()" class="btn btn-danger">IMPRIMIR</button>
        <button onclick="enviarTicketPorEmail()" class="btn btn-success">ENVIAR</button>
        <button onclick="$('#RESUMEN').addClass('d-none');nuevaVenta();" class="btn btn-warning">CONTINUAR</button>
    </div>
</div>



<script>
    function actualizarResumenVenta() {



        // Actualizar Datos de Modalidad de pago para RESUMEN
        //TIPO DE PAGO
        let modalidadDePago = $("#FORMAPAGO").val();
        $("#VENTA-FORMA-PAGO").val(modalidadDePago);
        $("#VENTA-TOTAL-RESUMEN").val($("#TOTAL-VENTA").val());
        //Si es efectivo
        if (modalidadDePago == "EFECTIVO") {


            $("#VENTA-ENTREGA-RESUMEN").val($("input[name=IMPORTE_PAGO]").val());
            $("#VENTA-VUELTO-RESUMEN").val($("input[name=VUELTO]").val());
        }
        //Si es Tarjeta
        if (modalidadDePago == "TARJETA") {
            $("#TAR_CUENTA").val($("input[name=TAR_CUENTA]").val());
            $("#TAR_BANCO").val($("input[name=TAR_BANCO]").val());
            $("#TAR_CEDULA").val($("input[name=TAR_CEDULA]").val());
            $("#TAR_BOLETA").val($("input[name=TAR_BOLETA]").val());
        }
        if (modalidadDePago == "TIGO_MONEY") {
            $("#GIRO_TELEFONO").val($("input[name=GIRO_TELEFONO]").val());
            $("#GIRO_CEDULA").val($("input[name=GIRO_CEDULA]").val());
            $("#GIRO_TITULAR").val($("input[name=GIRO_TITULAR]").val());
            $("#GIRO_FECHA").val($("input[name=GIRO_FECHA]").val());
        }
    }

    function mostrarResumenVenta() {
        let modalidadDePago = $("#FORMAPAGO").val();
        $("#RESUMEN").removeClass("d-none");
        if (modalidadDePago == "TARJETA") {
            $("#RESUMEN-PAGO-GIRO").addClass("d-none");
            $("#RESUMEN-PAGO-TARJETA").removeClass("d-none");
        }

        if (modalidadDePago == "TIGO_MONEY") {
            $("#RESUMEN-PAGO-GIRO").removeClass("d-none");
            $("#RESUMEN-PAGO-TARJETA").addClass("d-none");
        }
    }



    async function enviarTicketPorEmail( idd) {

        let idv = ultimoIdVentaRegistrado;
        let req = await fetch("<?= url("ventas/ticket") ?>/" + idd, {
            headers: {
                formato: "email"
            }
        });
        let resp = await req.json();
        if( "ok" in resp)
        alert("Enviado!");
        else alert(  resp.err) ;

    }
</script>
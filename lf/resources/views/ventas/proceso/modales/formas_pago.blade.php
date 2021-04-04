<style>
    #FORMAS-DE-PAGO label {
        color: black;
        font-weight: 600;
        font-family: titlefont !important;
    }

    #FORMAS-DE-PAGO .form-control entero {
        color: black;
        background-color: #999999 !important;
        height: 30px
    }

    #FORMAS-DE-PAGO fieldset label {
        font-size: 12px;
    }

    #FORMAS-DE-PAGO fieldset legend {
        font-size: 14px;
        background-color: red;
        color: white;
        border: 3px red solid;
        
        text-align: center;
    }
    #FORMAS-DE-PAGO fieldset{
        border: 1px #7c7c7c solid;
        padding: 1px; 
        height: 100%;
    }
</style>
<style>
     #VENTAS_DATOS_EFECTIVO div:nth-of-type(1) input{
        border-bottom: 1px solid black !important;
     }
    #VENTAS_DATOS_EFECTIVO  label {
        text-align: left;
        font-family: titlefont !important;
        font-weight: 600;
        color: #000000 !important;
        width: 100%;
    }

    #VENTAS_DATOS_EFECTIVO input,
    #TOTAL-VENTA,
    #VENTAS_DATOS_EFECTIVO label.MONTO {

        border: none !important;
        text-align: right;
        color: red !important;
        width: 100%;
        font-family: titlefont !important;
        font-weight: 600;
    }




    /* Medium devices (landscape tablets, 768px and up) */
    @media only screen and (min-width: 768px) {

        #VENTAS_DATOS_EFECTIVO input,
        #TOTAL-VENTA,
        #VENTAS_DATOS_EFECTIVO label.MONTO,
        #VENTAS_DATOS_EFECTIVO label {
            font-size: 14px;
        }
    }


    /* Large devices (laptops/desktops, 992px and up) */
    @media only screen and (min-width: 992px) {

        /**Font Size Lg */
        #VENTAS_DATOS_EFECTIVO input,
        #TOTAL-VENTA,
        #VENTAS_DATOS_EFECTIVO label.MONTO,
        #VENTAS_DATOS_EFECTIVO label {
            font-size: 18px !important;
        }

    }


    /* Extra large devices (large laptops and desktops, 1200px and up) */
    @media only screen and (min-width: 1200px) {

        /**Font Size Lg */
        #VENTAS_DATOS_EFECTIVO input,
        #TOTAL-VENTA,
        #VENTAS_DATOS_EFECTIVO label.MONTO {
            font-size: 18px;
        }
    }
</style>



<div id="FORMAS-DE-PAGO" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header p-0">
                <h5 class="modal-title">Formas de pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span style="color:black" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


                <div class="row no-gutters">

                    <div id="VENTA-HEADER-FORMA" class="col-12 ">
                        <label>Forma de pago:</label>

                        <select style="border-radius: 20px;border: #555 1px solid;font-size: 18px;padding: 0px;" id="FORMAPAGO" name="FORMA">
                            @php
                            $option_pago= [ "EFECTIVO"=> "EFECTIVO", "TARJETA"=> "TARJETA DE CRÉDITO O DÉBITO", "TIGO_MONEY"=> "TIGO MONEY" ];
                            @endphp

                            @foreach( $option_pago as $num => $val)
                            <option value="{{$num}}"> {{$val}} </option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-12">


                        <div id="VENTAS_DATOS_EFECTIVO" class="container-fluid" style="display: flex;justify-content: center;flex-direction: column; ">
                            
                            <div style="display: flex; flex-direction: row;">
                                <label>ENTREGA:</label>
                                <input value="0" type="text" id="IMPORTE_PAGO" name="IMPORTE_PAGO" class="entero MONTO" />
                            </div>
                            <div style="display: flex; flex-direction: row;">
                                <label>VUELTO:&nbsp;</label>
                                <input value="0" readonly type="text" id="VUELTO" name="VUELTO" class=" entero MONTO" />
                            </div>

                        </div>
                    </div>

                    <div class="col-12 col-md-6 mr-0" id="VENTAS_DATOS_TARJETA">
                        <fieldset style="display: flex; flex-direction: column;">
                            <legend>Tarjeta</legend>
                            <label for="">N° CUENTA:</label>
                            <input type="text" name="TAR_CUENTA">
                            <label for="">BANCO:</label>
                            <input type="text" name="TAR_BANCO">
                            <label for="">CÉDULA:</label>
                            <input type="text" name="TAR_CEDULA">
                            <label for="">N° BOLETA:</label>
                            <input type="text" name="TAR_BOLETA">
                        </fieldset>
                    </div>


                    <div class="col-12 col-md-6 ml-0" id="VENTAS_DATOS_GIRO">
                        <fieldset style="display: flex; flex-direction: column;">
                            <legend>Giros tigo</legend>
                            <label for="">N° TELÉFONO:</label>
                            <input type="text" name="GIRO_TELEFONO">
                            <label for="">CÉDULA:</label>
                            <input type="text" name="GIRO_CEDULA">
                            <label for="">TITULAR:</label>
                            <input type="text" name="GIRO_TITULAR">
                            <label for="">FECHA/HORA DE TRANS.:</label>
                            <input type="text" name="GIRO_FECHA">
                        </fieldset>
                    </div>


                    <div class="col-12">
                        <button onclick="$('#FORMAS-DE-PAGO').modal('hide');guardarVenta();" type="button" class="btn btn-success " data-dismiss="modal" aria-label="Close">
                            PAGAR
                        </button>
                    </div>

                </div>





            </div>

        </div>
    </div>
</div>
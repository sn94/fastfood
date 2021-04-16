<style>
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

    #FORMAS-DE-PAGO fieldset {
        border: 1px #7c7c7c solid;
        padding: 1px;
        height: 100%;
    }
</style>




<div id="FORMAS-DE-PAGO" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title">Formas de pago</h5>


                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <div class="row g-0">

                    <div class="col-12 ">

                        <select id="FORMAPAGO" class="form-control fs-2" name="FORMA">
                            @php
                            $option_pago= [ "EFECTIVO"=> "EFECTIVO", "TARJETA"=> "TARJETA DE CRÉDITO O DÉBITO", "TIGO_MONEY"=> "TIGO MONEY" ];
                            @endphp

                            @foreach( $option_pago as $num => $val)
                            <option value="{{$num}}"> {{$val}} </option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-12">


                        <div class="container-fluid" style="display: flex;justify-content: center;flex-direction: column; ">

                            <div class="row">
                                <div class="col  col-md-4">
                                    <label class="fs-3">ENTREGA:</label>
                                </div>
                                <div class="col col-md-8">
                                    <input value="0" type="text" id="IMPORTE_PAGO" name="IMPORTE_PAGO" class="form-control fs-3 entero MONTO" />
                                </div>
                            </div>


                            <div class="row">
                                <div class="col col-md-4">
                                    <label  class="fs-3">VUELTO:&nbsp;</label>
                                </div>
                                <div class="col col-md-8">
                                    <input value="0" readonly type="text" id="VUELTO" name="VUELTO" class="form-control fs-3 entero MONTO" />
                                </div>
                            </div>



                        </div>
                    </div>

                    <div class="col-12 col-md-6 mr-0">
                        <fieldset style="display: flex; flex-direction: column;">
                            <legend class="fs-5">Tarjeta</legend>
                            <label  class=" fs-5" for="">N° CUENTA:</label>
                            <input class="form-control fs-5" type="text" name="TAR_CUENTA">
                            <label  class=" fs-5"  for="">BANCO:</label>
                            <input class="form-control  fs-5" type="text" name="TAR_BANCO">
                            <label   class=" fs-5" for="">CÉDULA:</label>
                            <input class="form-control   fs-5" type="text" name="TAR_CEDULA">
                            <label  class=" fs-5" for="">N° BOLETA:</label>
                            <input class="form-control  fs-5" type="text" name="TAR_BOLETA">
                        </fieldset>
                    </div>


                    <div class="col-12 col-md-6 ml-0" id="VENTAS_DATOS_GIRO">
                        <fieldset style="display: flex; flex-direction: column;">
                            <legend  class="fs-5">Giros tigo</legend>
                            <label class=" fs-5" for="">N° TELÉFONO:</label>
                            <input class="form-control  fs-5" type="text" name="GIRO_TELEFONO">
                            <label  class=" fs-5" for="">CÉDULA:</label>
                            <input class="form-control  fs-5" type="text" name="GIRO_CEDULA">
                            <label  class=" fs-5"  for="">TITULAR:</label>
                            <input class="form-control  fs-5" type="text" name="GIRO_TITULAR">
                            <label  class=" fs-5" for="">FECHA/HORA DE TRANS.:</label>
                            <input class="form-control  fs-5" type="text" name="GIRO_FECHA">
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
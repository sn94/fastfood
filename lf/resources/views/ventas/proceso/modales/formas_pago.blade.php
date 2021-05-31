 



<div id="FORMAS-DE-PAGO" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 650px !important;max-width: 700px;">
        <div class="modal-content "  >
            <div class="modal-header ">  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <div class="row g-0">

                    <div class="col-12">

                        <div class="d-flex flex-row align-items-center ">

                            <select id="FORMAPAGO" class="form-select fs-4 p-1" name="FORMA">
                                @php
                                $option_pago= [ "EFECTIVO"=> "EFECTIVO", "TARJETA"=> "TARJETA DE CRÉDITO O DÉBITO", "TIGO_MONEY"=> "TIGO MONEY" ];
                                @endphp

                                @foreach( $option_pago as $num => $val)
                                <option value="{{$num}}"> {{$val}} </option>
                                @endforeach
                            </select>


                            <button onclick="$('#FORMAS-DE-PAGO').modal('hide');guardarVenta();" type="button" class="btn btn-success fs-5" data-dismiss="modal" aria-label="Close">
                                PAGAR
                            </button>
                        </div>
                    </div>
                    <div class="col-12">


                        <div class="container-fluid d-flex justify-content-center flex-column ">

                            <div class="row mb-1 mt-1">
                              <div class="col-12 col-md-6">
                              <div class="d-flex flex-row">
                                    <label style="width: 120px !important;" class="fs-4  ">Entrega:</label>
                                
                                    <input onfocus="this.value='';" onblur="if(this.value=='') this.value='0';" value="0" type="text" id="IMPORTE_PAGO" name="IMPORTE_PAGO" class="form-control fs-4 entero MONTO" />
                                </div>
                              </div>

                              <div class="col-12 col-md-6">
                              <div class="d-flex flex-row">
                              <label style="width: 120px !important;"  class="fs-4  ">Vuelto:&nbsp;</label>
                                   <input value="0" readonly type="text" id="VUELTO" name="VUELTO" class="form-control fs-4 entero MONTO" />
                              </div>
                              </div>

                            </div>
 



                        </div>
                    </div>

                   <div class="col-12">
                       <div class="row">
                       <div class="col-12 col-md-6 mr-0 mt-1">
                        <fieldset class="d-flex  flex-column">
                            <legend class="fs-5">Tarjeta</legend>
                            <div class="d-flex flex-row">
                                <label class=" fs-5" for="">N° Cuenta:</label>
                                <input class="form-control fs-5" type="text" name="TAR_CUENTA">
                            </div>
                            <div class="d-flex flex-row">
                                <label class=" fs-5" for="">Banco:</label>
                                <input class="form-control  fs-5" type="text" name="TAR_BANCO">
                            </div>
                            <div class="d-flex flex-row">
                                <label class=" fs-5" for="">Cédula:</label>
                                <input class="form-control   fs-5" type="text" name="TAR_CEDULA">
                            </div>
                            <div class="d-flex flex-row">
                                <label class=" fs-5" for="">N° Boleta:</label>
                                <input class="form-control  fs-5" type="text" name="TAR_BOLETA">
                            </div>
                        </fieldset>
                    </div>


                    <div class="col-12 col-md-6  mt-1" id="VENTAS_DATOS_GIRO">
                        <fieldset>
                            <legend class="fs-5">Giros tigo</legend>
                            <div class="d-flex flex-row">
                                <label class=" fs-5" for="">N° Teléfono:</label>
                                <input class="form-control  fs-5" type="text" name="GIRO_TELEFONO">
                            </div>
                            <div class="d-flex flex-row">
                                <label class=" fs-5" for="">Cédula:</label>
                                <input class="form-control  fs-5" type="text" name="GIRO_CEDULA">
                            </div>
                            <div class="d-flex flex-row">
                                <label class=" fs-5" for="">Titular:</label>
                                <input class="form-control  fs-5" type="text" name="GIRO_TITULAR">
                            </div>
                            <div class="d-flex flex-row">
                                <label class=" fs-5" for="">Fecha/Hora de Trans.:</label>
                                <input class="form-control  fs-5" type="text" name="GIRO_FECHA">
                            </div>

                        </fieldset>
                    </div>
                       </div>
                   </div>




                </div>





            </div>

        </div>
    </div>
</div>
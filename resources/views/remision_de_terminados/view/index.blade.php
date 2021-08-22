 

@php

//Url para procesar el form 
$urlAction=  isset( $REMISION)  ?  url("remision-prod-terminados/update")  :  url("remision-prod-terminados/create");

$REGNRO= isset($REMISION) ? $REMISION->REGNRO : '';
$NUMERO= isset($REMISION) ? $REMISION->NUMERO : '';
$FECHA= isset($REMISION) ?   $REMISION->FECHA->format('Y-m-d') : date('Y-m-d');
$AUTORIZADO_POR= isset($REMISION) ? $REMISION->AUTORIZADO_POR : '';
$CONCEPTO= isset($REMISION) ? $REMISION->CONCEPTO : '';
$PRODUCCION_ID= isset($REMISION) ? $REMISION->PRODUCCION_ID : ( isset($PRODUCCION) ? $PRODUCCION->REGNRO : '');
@endphp




 


<style>

.form-control {
       
       height: 25px !important;
     
   }

   input:disabled {
       background-color: #7d7d7d !important;
   } 

</style>

 

<div class="container-fluid">
    
        <div class="row">

            <div class="col-12 ">
                @if( isset($PRODUCCION))
                @includeIf('remision_de_terminados.view.ficha_produccion_view', ['PRODUCCION' => $PRODUCCION])
                @endif


                <div class="row">

                    @if( isset($REMISION))
                    <div class="col-12 col-md-6  mb-1">
                        <label class="mt-1 fs-6" for="element_7">Nota N°: </label>
                        <input name="REGNRO" class="form-control mt-1 fs-6 text-center" type="text"  readonly value="{{$REGNRO}}" />
                    </div>
                    @endif

                    <div class="col-12 col-md-6 mb-1">
                        <label class="mt-1 fs-6" for="element_7">Número doc.: </label>
                        <input readonly name="NUMERO" value="{{$NUMERO}}" class="form-control mt-1 fs-6" type="text" maxlength="15" />

                    </div>

                    <div class="col-12 col-md-6  mb-1">
                        <label class="mt-1 fs-6" for="element_7">Fecha: </label>
                       
                        <input readonly value="{{$FECHA}}" name="FECHA" class="form-control mt-1 fs-6" type="date" />

                    </div>

                    <div class="col-12   mb-1">
                        <label class="mt-1 fs-6" for="element_7">Autorizado Por: </label>
                        <input readonly maxlength="50" value="{{$AUTORIZADO_POR}}" name="AUTORIZADO_POR" class="form-control mt-1 fs-6" type="text" />
                    </div>

                    <div class="col-12  mb-1">
                        <label class="mt-1 fs-6" for="element_7">Observación: </label>
                        <input readonly value="{{$CONCEPTO}}" name="CONCEPTO" class="form-control mt-1 fs-6" type="text" />
                    </div> 
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 ">
                @include("remision_de_terminados.view.grill")
            </div>
        </div>
    

</div>
 
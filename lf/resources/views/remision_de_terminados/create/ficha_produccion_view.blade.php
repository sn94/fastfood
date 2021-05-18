<div class="row"  >

    <div class="col-12 col-sm-6 col-md-3" >

       <label class="fs-6"> Ficha de Prod. N°</label> <input  class="form-control fs-6"  readonly type="text" value="{{$PRODUCCION->REGNRO}}">
 

    </div>
    <div class="col-12 col-sm-6 col-md-4" >
        <label class="fs-6" > Registrado por:</label> <input  class="form-control fs-6" readonly type="text" value="{{$PRODUCCION->registrador->NOMBRES}}">

    </div>
    <div class="col-12 col-sm-6 col-md-4" >
        <label  class="fs-6" > Recibido por:</label> <input class="form-control fs-6" readonly type="text" value="{{$PRODUCCION->despachador->NOMBRES}}">

    </div>
</div>
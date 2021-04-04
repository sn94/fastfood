<div class="row"  >

    <div class="col-12 col-sm-6 col-md-3" >

       <label for=""> FICHA DE PROD. N°</label> <input  class="form-control"  readonly type="text" value="{{$PRODUCCION->REGNRO}}">
 

    </div>
    <div class="col-12 col-sm-6 col-md-4" >
        <label for=""> REGISTRADO POR:</label> <input  class="form-control" readonly type="text" value="{{$PRODUCCION->registrador->NOMBRES}}">

    </div>
    <div class="col-12 col-sm-6 col-md-4" >
        <label for=""> RECIBIDO POR:</label> <input class="form-control" readonly type="text" value="{{$PRODUCCION->despachador->NOMBRES}}">

    </div>
</div>
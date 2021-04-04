<div class="row pb-1 "   style="background-color: #a8a8a8;" >

    <div class="col-12 col-sm-6 col-md-3" >

       <label style="font-weight: 600;color:black"  > FICHA DE PROD. N°</label> <input  class="form-control"  readonly type="text" value="{{$PRODUCCION->REGNRO}}">
 

    </div>
    <div class="col-12 col-sm-6 col-md-4" >
        <label  style="font-weight: 600;color:black" > REGISTRADO POR:</label> <input  class="form-control" readonly type="text" value="{{$PRODUCCION->registrador->NOMBRES}}">

    </div>
    <div class="col-12 col-sm-6 col-md-4" >
        <label  style="font-weight: 600;color:black" > RECIBIDO POR:</label> <input class="form-control" readonly type="text" value="{{$PRODUCCION->despachador->NOMBRES}}">

    </div>
</div>
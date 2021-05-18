<div class="row pb-1 "   style="background-color: #a8a8a8;" >

    <div class="col-12 col-sm-6 col-md-3" >

       <label class="fs-6" style="font-weight: 600;color:black"  > Ficha de prod. N°</label> <input  class="form-control fs-6"  readonly type="text" value="{{$PRODUCCION->REGNRO}}">
 

    </div>
    <div class="col-12 col-sm-6 col-md-4" >
        <label class="fs-6" style="font-weight: 600;color:black" > Registrado por:</label> <input  class="form-control fs-6" readonly type="text" value="{{$PRODUCCION->registrador->NOMBRES}}">

    </div>
    <div class="col-12 col-sm-6 col-md-4" >
        <label class="fs-6" style="font-weight: 600;color:black" > Recibido por:</label> <input class="form-control fs-6" readonly type="text" value="{{$PRODUCCION->despachador->NOMBRES}}">

    </div>
</div>
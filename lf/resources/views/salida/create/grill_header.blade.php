 

<div class="row  mt-2">
    <!--style="display: grid;  grid-template-columns: 10% 10% 15%  65%;"-->
    <div class="col-12  col-sm-7 col-md-8  mb-0 d-flex flex-row">



        <label class="pr-1 fs-6 pe-2">Ítem: </label>
        @if( !isset($PRODUCCION_DETALLE) )
        <a href="#" onclick="buscarItem();//buscarItemParaSalida()"><i class="fa fa-search"></i></a>
        @endif
        <input size="7" style="font-weight: 600; color: black;width: 50px; " class="form-control fs-6" type="text" id="SALIDA-ITEM-ID" disabled>

        <input disabled class="form-control  fs-6" style="width: 100% !important; height: 40px;" autocomplete="off" type="text" id="SALIDA-ITEM-DESC">

    </div>

    <div class="col-12 col-sm-5  col-md-4 d-flex flex-row">

        <label class="pr-1 fs-6">Cantidad: </label>
        <div class="d-flex flex-column ps-2">
            <input onkeydown="if(event.keyCode==13) {event.preventDefault(); cargar_tabla();}" style="grid-column-start: 2;" id="SALIDA-CANTIDAD" class="form-control decimal  fs-6" type="text" />
            <label class="MEDIDA" id="SALIDA-MEDIDA"></label>
        </div>
        <a href="#" onclick="cargar_tabla()"><i class="fa fa-download"></i></a>

    </div>

</div>
 <form onsubmit="pedir_recibir(event)" method="POST" action="{{url('pedidos/create')}}">
     @csrf
    <div class="row">
    <div class="col-12 ">
         <label style="font-size: 15px;">SOLICITADO POR:</label>
     </div>
     <div class="col-12">
         <input class="w-100" type="text" name="SOLICITADO_POR" maxlength="30">
     </div>
     <div class="col-12 ">
         <label style="font-size: 15px;">CANTIDAD:</label>
     </div>
     <div class="col-sm-6">
         <input style="width: 100px;"  required type="text" name="CANTIDAD" class="decimal" oninput="formatoNumerico.formatearDecimal(event)">
         @php
         $medida= is_null($STOCK->unidad_medida) ? "-": $STOCK->unidad_medida->DESCRIPCION ;
         @endphp

         <span class="badge badge-warning"  style="font-size: 18px;"  > {{$medida}}</span>
     </div>
     <div class=" col-sm-6 ">
     <button class="mt-2 btn btn-sm btn-success w-100" type="submit">GUARDAR</button>
     </div>
    </div>
     <input type="hidden" name="ITEM" value="{{$STOCK->REGNRO}}">

 </form>
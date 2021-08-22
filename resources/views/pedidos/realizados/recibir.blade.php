
@php 

$pedido=  $PEDIDO[0];
@endphp
<h6 style="color: black;" >PEDIDO N°{{$pedido->NPEDIDO_ID}}</h6>
<form  onsubmit="pedir_recibir(event)" method="POST" action="{{url('pedidos/recibir')}}">
    @csrf
   
    <label  style="font-size: 15px;">Cantidad recibida:</label>
    <input style="width: 80px;" type="text" name="CANTIDAD" class="decimal"  oninput="formatoNumerico.formatearDecimal(event)">

    <span style="font-size: 18px;" class="badge badge-success">{{ $pedido->stock->unidad_medida->DESCRIPCION}}</span>

    <input type="hidden" name="ITEM" value="{{$pedido->ITEM}}">
    <input type="hidden" name="NPEDIDO_ID" value="{{$pedido->NPEDIDO_ID}}">

    <button class="btn btn-sm btn-success" type="submit">OK</button>
</form>
 
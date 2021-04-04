<?php

use App\Helpers\Utilidades;
 
?>
<button onclick="agregarVariedadDeProducto()" type="button" class="btn btn-sm btn-success">AGREGAR VARIEDAD(+)</button>
<table class="table table-hover table-striped table-light" id="STOCK-CONFIG-PRECIOS">

    <thead>
        <tr>
            <th>VARIEDAD</th>
            <th>ENTERO</th>
            <th>MITAD</th>
            <th>PORCIÓN</th>
            <th></th>
        </tr>
    </thead>

    <tbody>

        @if( isset($DETALLE_PRECIOS) )
        @foreach( $DETALLE_PRECIOS as $precios)

        @php 
        $DESCRIPCION= $precios->DESCRIPCION;
        $ENTERO= Utilidades::number_f( $precios->ENTERO );
        $MITAD=  Utilidades::number_f( $precios->MITAD);
        $PORCION =  Utilidades::number_f($precios->PORCION);
        @endphp 

        <tr>
            <td> <input value="{{$DESCRIPCION}}" name="PRECIO_DESCR[]" type="text" maxlength="30" /> </td>
            <td  class="text-end"> <input value="{{$ENTERO}}" name="PRECIO_ENTERO[]" class="entero" type="text" /> </td>
            <td  class="text-end"> <input value="{{$MITAD}}" name="PRECIO_MITAD[]" class="entero" type="text" /> </td>
            <td  class="text-end"> <input value="{{$PORCION}}" name="PRECIO_PORCION[]" class="entero" type="text" /> </td>
            <td > <button type="button" onclick="eliminarVariedadDeProducto(this)" class="btn btn-sm btn-danger">(-)</button> </td>
        </tr>
        @endforeach
        @endif

    </tbody>
</table>


<script>
    function agregarVariedadDeProducto() {
        let numericInput= "oninput='formatoNumerico.formatearEntero(event)'";
        let row = `
        <tr>
    <td> <input  name="PRECIO_DESCR[]" type="text" maxlength="30" /> </td>
     <td> <input ${numericInput} name="PRECIO_ENTERO[]" class="entero text-end" type="text" />  </td> 
     <td> <input ${numericInput}  name="PRECIO_MITAD[]" class="entero text-end" type="text" />  </td> 
     <td> <input ${numericInput}  name="PRECIO_PORCION[]" class="entero text-end" type="text" />  </td> 
     <td> <button type="button" onclick="eliminarVariedadDeProducto(this)" class="btn btn-sm btn-danger">(-)</button> </td>  
    </tr>
     `;
        $("#STOCK-CONFIG-PRECIOS tbody").append(row);
    }

    function eliminarVariedadDeProducto(esto) {
        $(esto.parentNode.parentNode).remove();
    }
</script>
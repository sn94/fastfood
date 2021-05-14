 <?php
 

$MODULO_FLAG =    isset($_GET['m']) ? $_GET['m'] :  "";
$QUERY_FLAG =  $MODULO_FLAG == "c" ? "?m=c"  :  ""; 

 
use App\Helpers\Utilidades;

?>

@php
$ESTADO_STOCK= "table-success";
$MENSAJE_STOCK= "";

$STOCK_ACTUAL= $prov->ENTRADAS + $prov->ENTRADA_PE + $prov->ENTRADA_RESIDUO -($prov->SALIDAS + $prov->SALIDA_VENTA);


if( $STOCK_ACTUAL <= 0): $ESTADO_STOCK="table-danger" ; $MENSAJE_STOCK="(Sin stock)" ; endif; @endphp <tr class="{{$ESTADO_STOCK}}">

    @if( session("NIVEL") == "SUPER"  ||  session("NIVEL") == "GOD" )
    <td>
        <a style="color: black;" href="{{url('stock/update').'/'.$prov->REGNRO}}"> <i class="fas fa-edit"></i></a>
    </td>
    <td>
        <a onclick="delete_row(event)" style="color: black;" href="{{url('stock').'/'.$prov->REGNRO}}"> <i class="fa fa-trash"></i></a>
    </td>
    @endif

    <td>{{($prov->CODIGO=='' ? '****':  $prov->CODIGO)}}</td>
    <td>{{($prov->BARCODE=='' ? '****':  $prov->BARCODE)}}</td>
    <td>{{$prov->DESCRIPCION}} <span style="color:red;font-weight: 600;">{{$MENSAJE_STOCK}}</span> </td>



    <td class="text-end"> {{ $STOCK_ACTUAL}} 
    @if($MODULO_FLAG !=  "c")
     {{$prov->unidad_medida->DESCRIPCION}}
     @endif
      </td>
    <td class="text-end">{{ Utilidades::number_f( $prov->PVENTA) }}</td>
    @if( $MODULO_FLAG==  "c")
    <td class="text-end">{{ Utilidades::number_f( $prov->PVENTA_MITAD) }}</td>
    <td class="text-center">  {{$prov->VENDIDOXMITAD=="S" ? "SI" : "NO"}} </td>
    @endif

    </tr>
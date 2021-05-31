<?php

use App\Helpers\Utilidades;

?>
<style>
    #COMPRAS-TABLE thead tr th,
    #COMPRAS-TABLE tbody tr td,
    #COMPRAS-TABLE tfoot tr th {
        padding: 0px !important;
        font-weight: 600;
    }

    #COMPRA-DETALLE tr th {
        text-align: right !important;
    }
</style>
@php
$TOTAL_FACTURA= 0;
$TOTAL_IVA5 = 0;
$TOTAL_IVA10=0;
@endphp


<div class="row mt-2 pt-1 pb-2  ">
    <div class="col-12 col-md-12 ">
        <table id="COMPRAS-TABLE" class="table table-striped table-secondary text-dark">

            <thead>
                <tr style="font-family: mainfont;font-size: 18px;">
                    <th class='text-center'>CÓDIGO</th>
                    <th class='text-center'>Descripción</th>
                    <th class='text-end'>UNI.MED.</th>
                    <th class='text-end'>PRECIO</th>
                    <th class='text-end'> CANTIDAD</th>
                    <th class='text-end'>5 %</th>
                    <th class='text-end'>10 %</th>
                    <th  style="width: 100px;"></th>
                </tr>
            </thead>
            <tbody id="COMPRA-DETALLE">

                @if( isset($DETALLE) )


                @foreach( $DETALLE as $detail)
                <tr id="{{$detail->ITEM}}" class="{{$detail->stock->TIPO.'-class'}}">
                    <td class='text-center'>{{$detail->stock->CODIGO}}</td>
                    <td class='text-center'>{{$detail->stock->DESCRIPCION}}</td>
                    <td class='text-end' >{{$detail->stock->MEDIDA}}</td>
                    <td class='text-end'>{{ Utilidades::number_f($detail->P_UNITARIO) }}</td>
                    <td class='text-end'>{{ Utilidades::number_f($detail->CANTIDAD) }}</td>
                    <td class='text-end'>{{ Utilidades::number_f($detail->IVA5) }}</td>
                    <td class='text-end'>{{ Utilidades::number_f($detail->IVA10)}}</td>
                    <td style="width: 100px;display: flex;flex-direction: row; justify-content: center;">
                        <a class='mr-1 ml-1' style='color:black;' href='#' onclick='compraObj.onDeleteRowFromGrill( this )'> <i class='fa fa-trash'></i> </a>
                    </td>
                </tr>

                @php
                
                $TOTAL_IVA10+= $detail->IVA5 ==0 ? $detail->IVA10 : 0;
                $TOTAL_IVA5+= $detail->IVA10 ==0 ? $detail->IVA5 : 0;
                $TOTAL_FACTURA+= $detail->IVA5 +  $detail->IVA10;
                @endphp
                @endforeach
                @endif

            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5">
                    </th>

                    <th class='text-end' id="TOTAL5">{{ Utilidades::number_f($TOTAL_IVA5)}}</th>
                    <th class='text-end' id="TOTAL10">{{  Utilidades::number_f($TOTAL_IVA10) }}</th>
                    <th  style="width: 100px;"></th>
                </tr>
                <tr>
                    <th colspan="6">
                        TOTAL FACTURA:
                    </th>

                    <th   class='text-end' id="TOTALFACTURA"> {{  Utilidades::number_f($TOTAL_FACTURA)}}</th>
                    <th  style="width: 100px;"></th>
                </tr>
            </tfoot>
        </table>
    </div>



</div>
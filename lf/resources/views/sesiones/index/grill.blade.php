<style>
    table thead tr th,
    table tbody tr td {
        padding: 0px !important;
        padding-left: 2px !important;
        padding-right: 2px;
    }


    .text-end {
        text-align: right;
    }
</style>


<?php

$SESIONES =  isset($datalist) ? $datalist :  $SESIONES;
?>
<table class="table table-hover table-striped text-dark bg-warning">



    <thead style="font-family: mainfont;font-size: 18px;">
        <tr>
            <th></th>
            <th>Sucursal</th>
            <th>Sesión N°</th>
            <th>Apertura</th>
            <th>Cierre</th>
            <th>Caja</th>
            <th>Cajero</th>
            <th class="text-end">Monto Ini.</th>
            <th class="text-end">Tot.Efectivo</th>
            <th class="text-end">Tot.Tarjeta</th>
            <th class="text-end">Tot.Giros</th>
        </tr>
    </thead>

    <tbody>

        @foreach( $SESIONES as $sesion)
        <tr>
            <td>
                @if( $sesion->MOSTRAR_CERRAR == 'S')
                    @if( $sesion->ESTADO == "A")
                    <a href="{{url('sesiones/cerrar/'.$sesion->REGNRO)}}" class="btn btn-sm btn-danger">CERRAR</a>
                    @else
                    <a onclick="descargarArqueo(event)" href="{{url('sesiones/informe-arqueo/'.$sesion->REGNRO)}}" class="btn btn-sm btn-danger">ARQUEO</a>
                    @endif
                @else
                <a onclick="descargarArqueo(event)" href="{{url('sesiones/informe-arqueo/'.$sesion->REGNRO)}}" class="btn btn-sm btn-danger">ARQUEO</a>
                @endif
            </td>
            <td>{{$sesion->SUCURSAL}}</td>

            <td>{{$sesion->REGNRO}}</td>
            <td>{{is_null( $sesion->FECHA_APE) ? "-"  :  $sesion->FECHA_APE->format('d/m/Y')}}</td>
            <td>{{ is_null($sesion->FECHA_CIE) ?  "-" : $sesion->FECHA_CIE->format('d/m/Y')}}</td>
            <td>{{$sesion->CAJA}}</td>
            <td>{{$sesion->cajero->NOMBRES}}</td>
            <td class="text-end">{{$sesion->EFECTIVO_INI}}</td>
            <td class="text-end">{{$sesion->TOTAL_EFE}}</td>
            <td class="text-end">{{$sesion->TOTAL_TAR}}</td>
            <td class="text-end">{{$sesion->TOTAL_GIRO}}</td>
        </tr>
        @endforeach
    </tbody>

</table>
{{$SESIONES->links('vendor.pagination.default')}}
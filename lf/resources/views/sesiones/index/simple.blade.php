<style>
h4{
    text-align: center;
}
    table thead tr th, table tbody tr td{
padding: 0px !important;
padding-left: 2px !important;
padding-right: 2px;
font-size: 10px;
    }


    .text-end{
        text-align: right;
    }
</style>


<?php 

$SESIONES=  isset($datalist) ? $datalist :  $SESIONES;
?>

<h4>Sesiones de cajeros</h4>
<table class="table bg-warning bg-gradient    text-dark">

 

    <thead style="font-family: mainfont;font-size: 18px;">
        <tr>
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
        <td>{{$sesion->SUCURSAL}}</td>
            <td>{{$sesion->REGNRO}}</td>
            <td>{{$sesion->FECHA_APE->format('d/m/Y')}}</td>
            <td>{{$sesion->FECHA_CIE->format('d/m/Y')}}</td>
            <td>{{$sesion->CAJA}}</td>
            <td>{{$sesion->cajero->NOMBRES}}</td>
            <td class="text-end">{{$sesion->EFECTIVO_INI}}</td>
            <td class="text-end">{{$sesion->TOTAL_EFE}}</td>
            <td class="text-end" >{{$sesion->TOTAL_TAR}}</td>
            <td class="text-end">{{$sesion->TOTAL_GIRO}}</td>
        </tr>
        @endforeach
    </tbody>

</table>
 
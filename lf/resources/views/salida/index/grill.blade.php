<style>
table thead tr th, table tr td{
padding:  0px 2px  !important;
}
</style>


@if( isset( $print_mode ))
@include("templates.print_report")
<h4 style="text-align: center;">{{$titulo}}</h4>
@endif


<table id="SALIDA-TABLE" class="table table-striped table-hover bg-warning text-dark">
    <thead>
        <tr>
        <th></th>
        <th></th>
            <th>N°</th>
            <th>Fecha</th>
            <th>Tipo Salida</th>
            <th>Destino</th>
            <th>Suc. Destino</th>
            <th>N° producción</th>
            <th>N° pedido</th>
        </tr>
    </thead>
    <tbody>

        @if( isset($SALIDAS))
        @foreach( $SALIDAS as $SALIDA_)
        <tr>
        <td>
                <a style="color: black;" href="{{url('salida/update').'/'.$SALIDA_->REGNRO}}"> <i class="fas fa-edit"></i></a>
            </td>
            <td>
                <a onclick="delete_row(event)" style="color: black;" href="{{url('salida').'/'.$SALIDA_->REGNRO}}"> <i class="fa fa-trash"></i></a>
            </td>
        <td>{{   $SALIDA_->NUMERO == ''  ? '****' :  $SALIDA_->NUMERO}}</td>
        <td>{{   is_null($SALIDA_->FECHA) ? '****':  $SALIDA_->FECHA->format('d/m/Y')}}</td>
        <td>
            @php 
            $tipo_salida= $SALIDA_->TIPO_SALIDA;
            @endphp

        <x-tipo-stock-chooser name="" callback="" :value="$tipo_salida"  readonly="S"  style="border:none;" />
            

        </td>
        <td>{{$SALIDA_->DESTINO == "" ? '****' : $SALIDA_->DESTINO }}</td>
        <td>{{$SALIDA_->SUCURSAL_DESTINO == "" ? '****' :  $SALIDA_->SUCURSAL_DESTINO }}</td>
        <td>{{$SALIDA_->PRODUCCION_ID == "" ? '****' :  $SALIDA_->PRODUCCION_ID  }}</td>
        <td>{{$SALIDA_->PEDIDO_ID  == "" ? '****' :  $SALIDA_->PEDIDO_ID  }}</td>
        </tr>
        @endforeach
        @endif


    </tbody>
</table>

<x-pretty-paginator   :datos="$SALIDAS"  callback="fill_grill"/>

 
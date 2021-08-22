<style>
    table thead tr th,
    table tr td {
        padding: 0px 2px !important;
    }
</style>


@if( isset( $print_mode ))
@include("templates.print_report")
@endif

<h4 style="text-align: center;">Notas de remisión</h4>
<table id="REMISION-TABLE" class="table table-striped table-hover fast-food-table text-dark">
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th>N°</th>
            <th>Fecha</th>

            <th>N° producción</th>
            <th>Autorizado por</th>
            <th>Concepto</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>

        @if( isset($REMISION))
       
        @foreach( $REMISION as $REMISION_)
        
        <tr>

            <td>
                <a style="color: black;" href="{{url('remision-prod-terminados/update').'/'.$REMISION_->REGNRO}}"> <i class="fas fa-edit"></i></a>
            </td>
            <td>
                <a onclick="delete_row(event)" style="color: black;" href="{{url('remision-prod-terminados').'/'.$REMISION_->REGNRO}}"> <i class="fa fa-trash"></i></a>
            </td>

            <td>{{ $REMISION_->NUMERO == ''  ? '' :  $REMISION_->NUMERO}}</td>
            <td>{{ is_null($REMISION_->FECHA) ? '':  $REMISION_->FECHA->format('d/m/Y')}}</td>


            <td>{{$REMISION_->PRODUCCION_ID == "" ? '' :  $REMISION_->PRODUCCION_ID  }}</td>
            <td>{{$REMISION_->AUTORIZADO_POR }} </td>
            <td> {{$REMISION_->CONCEPTO == ""  ?  '' :   $REMISION_->CONCEPTO }} </td>
            <td class=" {{$REMISION_->ESTADO == 'P'  ?  'text-danger fw-bold' :   'text-success fw-bold' }}">
             {{$REMISION_->ESTADO == "P"  ?  'Pendiente' :   'Aceptada' }}
              </td>
        </tr>
        @endforeach
        @endif


    </tbody>
</table>
 
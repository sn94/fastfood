<style>
    table thead tr th,
    table tr td {
        padding: 0px 2px !important;
    }
</style>


@if( isset( $print_mode ))
@include("templates.print_report")
@endif

<h4 style="text-align: center;">Notas de residuo</h4>
<table id="SALIDA-TABLE" class="table table-striped table-hover bg-warning text-dark">
    <thead>
        <tr>
        <th></th>
        <th></th>
            <th>N°</th>
            <th>Fecha</th>

            <th>N° producción</th>
            <th>Registrado por</th>
            <th>Concepto</th>
        </tr>
    </thead>
    <tbody>

        @if( isset($NOTAS_RESIDUOS))
        @foreach( $NOTAS_RESIDUOS as $NOTAS_RESIDUOS_)
        <tr>

        <td>
                <a style="color: black;" href="{{url('nota-residuos/update').'/'.$NOTAS_RESIDUOS_->REGNRO}}"> <i class="fas fa-edit"></i></a>
            </td>
            <td>
            <a onclick="delete_row(event)" style="color: black;" href="{{url('nota-residuos').'/'.$NOTAS_RESIDUOS_->REGNRO}}"> <i class="fa fa-trash"></i></a>
            </td>



            <td>{{ $NOTAS_RESIDUOS_->NUMERO == ''  ? '****' :  $NOTAS_RESIDUOS_->NUMERO}}</td>
            <td>{{ is_null($NOTAS_RESIDUOS_->FECHA) ? '****':  $NOTAS_RESIDUOS_->FECHA->format('d/m/Y')}}</td>


            <td>{{$NOTAS_RESIDUOS_->PRODUCCION_ID == "" ? '****' :  $NOTAS_RESIDUOS_->PRODUCCION_ID  }}</td>
            <td>{{$NOTAS_RESIDUOS_->REGISTRADO_POR ==  "" ? "*****" : $NOTAS_RESIDUOS_->REGISTRADO_POR  }} </td>
            <td> {{$NOTAS_RESIDUOS_->CONCEPTO == ""  ?  "*********" :   $NOTAS_RESIDUOS_->CONCEPTO }}  </td>
        </tr>
        @endforeach
        @endif


    </tbody>
</table>
 <script>
 
 
 async function delete_row(ev) {
        ev.preventDefault();
        if (!confirm("continuar?")) return;
        let req = await fetch(ev.currentTarget.href, {
            "method": "DELETE",
            headers: {

                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: "_method=DELETE&_token=" + $('meta[name="csrf-token"]').attr('content')

        });
        let resp = await req.json();
        if ("ok" in resp) fill_grill();
        else alert(resp.err);

    }
 </script>
<x-pretty-paginator :datos="$NOTAS_RESIDUOS" callback="fill_grill" />
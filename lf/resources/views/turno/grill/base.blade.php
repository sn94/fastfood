<style>
    table thead tr th,
    table tbody tr td {
        padding: 0px !important;
        padding-left: 2px !important;
        padding-right: 2px;
    }
 
</style>

<table class="table bg-warning bg-gradient    text-dark">

    <thead >
        <tr>
            <th></th>
            <th></th>
            <th>ID</th>
            <th>DESCRIPCIÓN</th>
            <th>ORDEN</th>
        </tr>
    </thead>

    <tbody>

        @foreach( $turnos as $prov)
        <tr>
            <td>
                <a  onclick="edit_row(event)" style="color: black;" href="{{url('turno/update').'/'.$prov->REGNRO}}"> <i class="fas fa-edit"></i></a>
            </td>
            <td>
                <a onclick="delete_row(event)" style="color: black;" href="{{url('turno').'/'.$prov->REGNRO}}"> <i class="fa fa-trash"></i></a>
            </td>


            <td>{{$prov->REGNRO}}</td>
            <td>{{$prov->DESCRIPCION}}</td>
            <td>{{$prov->ORDEN}}</td>


        </tr>
        @endforeach
    </tbody>

</table>
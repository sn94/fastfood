<style>
    h4 {
        text-align: center;
    }

    table {

        font-family: Arial, Helvetica, sans-serif;
    }

    table thead tr th,
    table tbody tr td {
        padding: 0px;

    }



    table thead tr th {
        font-size: 11px;
        border-bottom: 1px solid black;
    }

    table tbody tr td,
    table tbody tr th {
        font-size: 10px;
    }
</style>



<h4>{{$titulo}}</h4>
<table class="table fast-food-table bg-gradient    text-dark">
    <thead style="font-family: mainfont;font-size: 18px;">
        <tr>

            <th>CÉDULA/RUC</th>
            <th>RAZÓN SOCIAL</th>
            <th>DOMICILIO</th>
            <th>CIUDAD</th>
            <th>TELÉF.</th>
            <th>CELULAR</th>
        </tr>
    </thead>

    <tbody>

        @foreach( $datalist as $cli)
        <tr>

            <td>{{$cli->CEDULA_RUC}}</td>
            <td>{{$cli->NOMBRE}}</td>
            <td>{{$cli->DIRECCION}}</td>
            <td>{{ is_null( $cli->ciudad_) ? "" :  $cli->ciudad_->ciudad}}</td>
            <td>{{$cli->TELEFONO}}</td>
            <td> {{$cli->CELULAR}}</td>

        </tr>
        @endforeach
    </tbody>

</table>
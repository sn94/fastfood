<style>
    h4{
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

    table tbody tr td {
        font-size: 10px;
    }
</style>
<h4 >{{$titulo}}</h4>
<table class="table fast-food-table bg-gradient    text-dark">

    <thead style="font-family: mainfont;font-size: 18px;">
        <tr>
            <th>ID</th>
            <th>DEPARTAMENTO</th>
            <th>Descripción</th>
         
        </tr>
    </thead>

    <tbody>

        @foreach( $datalist as $prov)
        <tr>

            <td>{{$prov->REGNRO}}</td>
            <td>{{$prov->departamento->departa }}</td>
            <td>{{$prov->ciudad}}</td>
           


        </tr>
        @endforeach
    </tbody>

</table>
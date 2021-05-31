<table class="table table-striped table-hover  fast-food-table">
    <thead>
        <tr>
            <th></th> 
            <th>ELABORADO POR</th>
            <th>APROBADO POR</th>
            <th>FECHA</th>
            <th>DETALLE</th>
        </tr>
    </thead>

    <tbody>

        <!--VISTA DE TABLA PERSONALIZADA  -->
        @foreach( $ficha_produc as $ficha)
        <tr>
            <td>
                <a class="btn btn-sm  btn-success" href="{{url('salida/'.$ficha->REGNRO)}}"> APROBAR</a>
            </td>

           
            <td>{{$ficha->ELABORADO_POR}}</td>

            <td>{{  !is_null($ficha->despachador) ?   $ficha->despachador->NOMBRES : '' }}</td>

            <td>{{$ficha->FECHA }}</td>
            <td>
                @php
                foreach( $ficha->detalle_produccion as $ite):
                if( $ite->TIPO == "MP") echo $ite->stock->DESCRIPCION.",";
                endforeach;
                @endphp
                ...
            </td>
        </tr>
        @endforeach


    </tbody>
</table>
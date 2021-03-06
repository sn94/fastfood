<table class="table table-striped table-hover fast-food-table">
    <thead>
        <tr>
            <th></th>
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
                @if( $ACCION == "TERMINADOS")
                <a class="btn btn-sm  btn-success" href="{{url('remision-prod-terminados/'.$ficha->REGNRO)}}"> REM. DE PROD. TERMINADOS</a>

                @elseif( $ACCION == "RESIDUOS")
                <a class="btn btn-sm  btn-danger" href="{{url('nota-residuos/create/'.$ficha->REGNRO)}}"> REGISTRAR RESIDUOS</a>
                @endif
            </td>

            <td>
                <a class="btn btn-sm  btn-secondary" style="color: black;" href="{{url('ficha-produccion').'/'.$ficha->REGNRO}}">EDITAR
                </a>
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
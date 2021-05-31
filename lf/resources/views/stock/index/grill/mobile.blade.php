<?php

use App\Helpers\Utilidades;

?>


@foreach( $stock as $itemStock)



<div class="card" style="width: 100%;">


    <div class="card-body text-dark">

        <div class="row">
            <div class="col-6">
                @php

                $urlDeLaImagen= $itemStock->IMG_EXT_URL == '' ? $itemStock->IMG : $itemStock->IMG_EXT_URL;
                $urlImagenDefault= url('assets/images/default_food.png');
                @endphp
                <img style="width: 100%; height: auto;" src="{{$urlDeLaImagen}}" onerror="this.src='{{$urlImagenDefault}}';" alt="Sin Foto">
            </div>
            <div class="col-6 p-0">
                <p class="card-text">
                    <b style="font-size: 12.5px;color:black;">{{$itemStock->DESCRIPCION}}</b>
                    <span style="display:block;" class="badge bg-success">Precio de venta:
                     {{ Utilidades::number_f( $itemStock->PVENTA) }}</span>

                    <b style="font-size: 12px;display:block;">Cód.Barras: {{($itemStock->BARCODE=='') ? '': $itemStock->BARCODE}}</b>


                    @if( $itemStock->CANTIDAD <= 0 ) <span class="badge bg-danger">Sin stock </span>
                        @else
                        <span class="badge badge-success">Stock: {{ $itemStock->CANTIDAD}} &nbsp;</span>
                        @endif
                </p>

                @if( session("NIVEL") == "SUPER" || session("NIVEL") == "GOD" )
                <a class="text-dark p-0"  style="text-decoration: none;" href="{{url('stock/update').'/'.$itemStock->REGNRO}}">
                    <i class="fas fa-edit"></i>
                    <span class="fw-bold">Editar</span></a>


                <a class="text-dark p-0" style="text-decoration: none;" onclick="delete_row(event)"   href="{{url('stock').'/'.$itemStock->REGNRO}}">
                    <i class="fa fa-trash"></i><span class="fw-bold">Borrar</span></a>
                @endif
            </div>
        </div>



    </div>
</div>
@endforeach
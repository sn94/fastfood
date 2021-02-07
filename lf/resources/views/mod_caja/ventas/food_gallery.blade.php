<?php

use App\Helpers\Utilidades;

?>
@php
$BASE_ASSETS= url('assets');
@endphp





<div class="container-wrap">
    <div class="row no-gutters d-flex">

        @foreach( $productos as $item)

        <div class="col-12 col-md-3 col-lg-3 d-flex ftco-animate">
            <!--   style="background-image: url({{$item->IMG}}); background-position: center; background-size: contain;background-repeat: no-repeat;"
             -->
          <div class="services-wrap mb-2 bg-dark"> 

            <input type="hidden" id="iva{{$item->REGNRO}}"    value="{{$item->TRIBUTO}}" >
                <img onclick="cargar(this)" target="{{$item->REGNRO}}" style=" width: 100px; height: 100px;" src='{{url("$item->IMG")}}' class=" mx-auto d-block img-fluid  img-thumbnail" alt="">

                <span style="padding: 1px 10px; position: absolute;top: 0%;background: black;color: yellow;left: 25%;" id="precio{{$item->REGNRO}}">
                    {{Utilidades::number_f($item->PVENTA)}} </span>
                <div class=" text-center bg-dark text-light pb-0 "  >
                    <span id="descr{{$item->REGNRO}}" style="font-size: 12px;font-weight: 600;">{{$item->DESCRIPCION}}</span>


                </div>

         </div>
        </div>
        @endforeach



    </div>
</div>
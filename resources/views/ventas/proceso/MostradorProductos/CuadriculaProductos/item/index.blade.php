<?php

use App\Helpers\Utilidades;

?>

<div class="FOOD-CELL"  id="FOOD-CELL-{{$item->REGNRO}}"  >
    <!--   style="background-image: url({{$item->IMG}}); background-position: center; background-size: contain;background-repeat: no-repeat;"
             -->

    <input type="hidden" id="iva{{$item->REGNRO}}" value="{{$item->TRIBUTO}}">

@php 

$urlDeLaImagen=  $item->IMG_EXT_URL == '' ?   $item->IMG :   $item->IMG_EXT_URL;

@endphp
    <img onclick="cargarSegunPrecioNormal(<?= $item->REGNRO ?>)" target="{{$item->REGNRO}}" src='{{$urlDeLaImagen}}' class=" mx-auto d-block img-fluid  img-thumbnail" alt=""  onerror="this.src='{{url('assets/images/default_food.png')}}';" >


    <span class="text-white pt-1 descripcion"  >{{$item->DESCR_CORTA}}</span>

    <!--Configurado precio mitad-->

    @if( $item->PRECIOS_VARIOS == 'S' )
   <!-- <button onclick="mostrarPreciosVarios(<?= $item->REGNRO ?>)" class="btn  btn-sm btn-normal-price border border-1 border-warning" type="button">
        <img style="position: absolute;left: 80%;" src="<?= url('assets/icons/star_icon.png') ?>" alt="">
        {{ Utilidades::number_f($item->PVENTA) }}
    </button>

     Solo precios multiples -->
     @if(   sizeof($item->precios) > 0 )
    <button onclick="mostrarPreciosVarios(<?= $item->REGNRO ?>)" class="btn  btn-sm btn-special-price border border-1 border-warning" type="button">
        <img style="position: absolute;left: 80%;" src="<?= url('assets/icons/star_icon.png') ?>" alt="">
        {{ Utilidades::number_f($item->PVENTA) }}
    </button>
    @endif
   
    @else
    <button onclick="cargarSegunPrecioNormal(<?= $item->REGNRO ?>)" class="btn  btn-sm btn-normal-price border border-1 border-danger" type="button">
        {{ Utilidades::number_f($item->PVENTA) }}
    </button>
    @endif
</div>
 
@php
$BASE_ASSETS= url('assets');
@endphp

 
<div class="container-fluid">
    
</div>

<div class="row g-0 d-flex ml-2"     >
 
@foreach( $Stock as $item)

@if(     ($item->TIPO == "PE" || $item->TIPO == "PP") )
@include("ventas.proceso.detalle.food_gallery.grid_items.item.index")
@endif
@endforeach
    </div>
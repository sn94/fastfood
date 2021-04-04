 
@php
$BASE_ASSETS= url('assets');
@endphp

 
<div class="container-fluid">
    
</div>

<div class="row no-gutters d-flex bg-light">
 
@foreach( $Stock as $item)

@if(     ($item->TIPO == "PE" || $item->TIPO == "PP") )
@include("ventas.proceso.detalle.food_gallery.grid_items.item.index")
@endif
@endforeach
    </div>
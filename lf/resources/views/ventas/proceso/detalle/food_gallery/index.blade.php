 <?php

  use App\Models\Familia;

  //AGRUPAR PRODUCTOS POR FAMILIA
  $familiasDeProd = Familia::orderBy("NRO_PESTANA")->get();

  ?>



 <style>


.nav-tabs {
  background-color:  var(--color-3) !important;
}

   .nav-tabs .nav-item.show .nav-link,
   .nav-tabs .nav-link.active {
     color: #ff0000 !important;
     background-color: white !important;
   }

   .nav-tabs .nav-link {
     color: white !important;
     background-color:  var(--color-3) !important;
     border: 1px solid #f90000c7 !important;
   }

   .nav-link {
     color: #910808 !important;
     font-weight: 600 !important;
   }

   .nav-tabs .nav-link:focus,
   .nav-tabs .nav-link:hover {
     background: #2d2928 !important;
     color: white !important;
   }



 


 </style>


 @include("ventas.proceso.detalle.food_gallery.filtrar_productos")

 @include("ventas.proceso.detalle.food_gallery.grid_items.item.estilos")















 <ul id="FOOD-GALLERY" class="nav nav-tabs" id="myTab" role="tablist">

   @php
   $foodGalleryLinkActive= FALSE;
   @endphp

   @foreach( $familiasDeProd as $family)

   @php
   $familyDescription= join( '_', (explode( " ", $family->DESCRIPCION)) );
   $activo= "";
   if( !$foodGalleryLinkActive){
   $activo= " active ";
   $foodGalleryLinkActive= TRUE;}
   @endphp

   <li class="nav-item">
     <button type="button" role="presentation" class="nav-link {{$activo}}" id="{{$family->REGNRO}}-tab" data-bs-toggle="tab" data-bs-target="#{{$familyDescription}}" role="tab" aria-controls="{{$familyDescription}}" aria-selected="true">{{$familyDescription}}</button>
   </li>
   @endforeach

 </ul>
 <div class="tab-content" id="myTabContent">

   @php
   $foodGalleryPanelActive= FALSE;
   @endphp

   @foreach( $familiasDeProd as $family)

   @php
   $familyDescription= join( '_', (explode( " ", $family->DESCRIPCION)) );
   $activo= "";
   if( !$foodGalleryPanelActive){
   $activo= " show active ";
   $foodGalleryPanelActive= TRUE; }
   @endphp

   <div class="tab-pane fade {{$activo}}" id="{{$familyDescription}}" role="tabpanel" aria-labelledby="{{$family->REGNRO}}-tab">

     @include( "ventas.proceso.detalle.food_gallery.grid_items.index", ['Stock'=> $family->productos])

   </div>

   @endforeach

 </div>


 <!-- Funciones para elegir precios, y cargarlos en la tabla -->
 @include("ventas.proceso.detalle.food_gallery.precios.index")
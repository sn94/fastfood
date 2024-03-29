 <?php

  use App\Models\Familia;

  //AGRUPAR PRODUCTOS POR FAMILIA
  $familiasDeProd = Familia::orderBy("NRO_PESTANA")->where("MOSTRAR_EN_VENTA", "S")->get();

  ?>

 @include("ventas.proceso.MostradorProductos.filtrar_productos")
 












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

     @include( "ventas.proceso.MostradorProductos.CuadriculaProductos.index", ['Stock'=> $family->productos])

   </div>

   @endforeach

 </div>


 <!-- Funciones para elegir precios, y cargarlos en la tabla -->
 @include("ventas.proceso.MostradorProductos.precios.index")
<style>
  .user-info-card {
    position: absolute; 
    border-radius: 8px;
    background-image: url({{url('assets/images/login_wallpaper.jpg')}});
    color: white !important;
    animation-timing-function: ease-in-out;
    animation-duration: 2s;
    animation-iteration-count: infinite;
    animation-name: user-info;
  }

 
 
   
@keyframes user-info{
0%,100%{
  transform: rotate( 0deg);
}
50%{
 transform: rotate(45deg);
}
}
 
</style>



@php

use App\Models\Sucursal;

$suc= Sucursal::find( session('SUCURSAL') );
$nombreSuc= is_null( $suc) ? '' : $suc->DESCRIPCION;

@endphp
<div class="user-info-card card text-dark   mb-3" style="max-width: 18rem;">

  <div class="card-body">
    <h5> Sucursal: {{session('SUCURSAL')}} {{$nombreSuc}} </h5>
    <h5 class="card-title">Usuario: {{session("USUARIO")}} </h5>
    <p class="card-text"> <span class="badge bg-success"> {{session("NIVEL")}} </span> </p>
  </div>
</div>
<style>
  .user-info-card {
    position: absolute;

  }


  .user-info-card::before, .user-info-card::after{
    position: absolute !important;
    top: 0px;
    left: 0px;
    content: '';

 
    animation-timing-function: ease-in-out;
    animation-duration: 2s;
    animation-iteration-count: infinite;
  }
  .user-info-card::before{
    width: 100%;
    border-left: 2px solid #6a6a6a;
    border-right: 2px solid #6a6a6a;
    animation-name: user-info-before;
  }

  .user-info-card::after{
    height: 100%;
    border-top: 2px solid #6a6a6a;
    border-bottom: 2px solid #6a6a6a;
    animation-name: user-info-after; 
  }
@keyframes user-info-before{
from{
  top:0%;
  bottom: 0%;
}
to{
  top: 100%; 
  bottom: 100%;
}
}
@keyframes user-info-after{
from{
  right:0%;
  left: 0%;
}
to{
  right: 100%; 
  left: 100%;
}
}
</style>



@php

use App\Models\Sucursal;

$suc= Sucursal::find( session('SUCURSAL') );
$nombreSuc= is_null( $suc) ? '' : $suc->DESCRIPCION;

@endphp
<div class="user-info-card card text-dark fast-food-table mb-3" style="max-width: 18rem;">

  <div class="card-body">
    <h5> Sucursal: {{session('SUCURSAL')}} {{$nombreSuc}} </h5>
    <h5 class="card-title">Usuario: {{session("USUARIO")}} </h5>
    <p class="card-text"> <span class="badge bg-success"> {{session("NIVEL")}} </span> </p>
  </div>
</div>
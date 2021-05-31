
<style>

.card {
  position: absolute;
   
}
</style>



@php 

use App\Models\Sucursal;

$suc= Sucursal::find(   session('SUCURSAL') );
$nombreSuc=  is_null(  $suc) ?   '' :   $suc->DESCRIPCION;

@endphp
<div class="card text-dark fast-food-table mb-3" style="max-width: 18rem;">
 
  <div class="card-body">
<h5> Sucursal: {{session('SUCURSAL')}}  {{$nombreSuc}} </h5>
    <h5 class="card-title">Usuario: {{session("USUARIO")}} </h5>
    <p class="card-text"> <span class="badge bg-success"> {{session("NIVEL")}}  </span> </p>
  </div>
</div>
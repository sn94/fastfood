
<style>

.card {
  position: absolute;
   
}
</style>


<div class="card text-dark bg-warning mb-3" style="max-width: 18rem;">
 
  <div class="card-body">
<h5> Sucursal: {{session('SUCURSAL')}}</h5>
    <h5 class="card-title">Usuario: {{session("USUARIO")}} </h5>
    <p class="card-text"> <span class="badge bg-success"> {{session("NIVEL")}}  </span> </p>
  </div>
</div>
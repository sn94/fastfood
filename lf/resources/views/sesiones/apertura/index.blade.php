@extends("templates.caja.index")


@section("content")


<style>
  #SESION-FORM .form-control {
    height: 30px !important;
    background: white !important;
    font-size: 14px;
    color: black !important;
  }

  #SESION-FORM div.col-12:nth-child(7) {
    align-items: center;
  }

  #SESION-FORM label {
    font-weight: 500;
    color: white;
    font-family: titlefont;
    font-size: 12px;
  }

  #SESION-FORM input.form-control[readonly] {
    background-color: #9b9a9a !important;
  }

  a i.fa-search,
  a i.fa-plus {
    background-color: #f7fb55;
    border-radius: 30px;
    padding: 5px;
    border: 1px solid black;
    color: black;
  }
</style>


@include("buscador.generico", ['TIPO'=>'TURNO'])


<div class="container col-12 col-md-10 col-lg-7 bg-dark mt-0 mb-0  mt-md-0 mb-md-0 mt-lg-3 mb-lg-3">
  
  
@if(  isset($SESION) )
<div class="alert alert-warning p-0">
<h5 class="text-dark">Tienes una sesión abierta <img src="<?=url('assets/icons/atencion.png')?>"  ></h5>

</div>
@endif 


  <form id="SESION-FORM" action="{{url('sesiones/create')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)">
    @include("sesiones.apertura.form",  ['SESION_ABIERTA' => "NO"])
  </form>

</div>


<script>


</script>
@endsection
@extends("templates.caja.index")


@section("content")


<style>
  /*
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
    font-size: 12px;
  }*/

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


<div class="container-fluid  col-12  col-sm-8 col-md-5 col-lg-5   d-flex flex-column text-light mt-2 pt-1">


  @if( isset($SESION) )
  <span class="badge bg-danger ml-3 p-0  align-self-end">Sesión abierta <img src="<?= url('assets/icons/atencion.png') ?>"></span>
  @endif


  <form class="bg-dark  p-2 p-sm-3 p-md-4 p-lg-5" id="SESION-FORM" action="{{url('sesiones/create')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)">
    @include("sesiones.apertura.form", ['SESION_ABIERTA' => "NO"])
  </form>

</div>


<script>


</script>
@endsection
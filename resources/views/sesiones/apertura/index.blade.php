@extends("templates.caja.index")


@section("content")


<style> 

 .open-session-span{
position: absolute !important;
transform: rotate(20deg) translateX(15px) !important;
margin-left: 3px !important;
padding: 0px 0px 2px 2px !important;
align-self: flex-end;
border-radius: 20px ;
background-color: var(--color-primario) !important;
color: white;
letter-spacing: 1px;
 }
   
</style>


@include("buscador.generico", ['TIPO'=>'TURNO'])


<div class="container-fluid  fast-food-bg  col-12  col-sm-8 col-md-5 col-lg-4   d-flex flex-column   mt-2 pb-5">


  @if( isset($SESION) )
  <span class="open-session-span"  >
    Sesión abierta <img src="<?= url('assets/icons/atencion.png') ?>">
  </span>
  @endif


  <form  id="SESION-FORM" action="{{url('sesiones/create')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)">
    @include("sesiones.apertura.form", ['SESION_ABIERTA' => "NO"])
  </form>

</div>


<script>


</script>
@endsection
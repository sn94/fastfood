@include("buscador.Buscador")

<div class="row   m-0 m-md-0   mb-lg-0 VENTA-HEADER">
  <div class="col-12  pb-1 d-flex flex-row">

    <label class=" text-light   fs-5 w-100">Sesión N°:</label>
    <input readonly value="{{session('SESION')}}" class="form-control p-0 text-center  fs-5" type="text">


    <label class=" text-light text-center pr-1 fs-5 w-100">Origen:</label>
    <x-origen-venta-list name="ORIGEN" value="" id="" callback="" style="" class="form-select p-0 text-center  fs-5">
    </x-origen-venta-list>
  </div>
  <div class="col-12">
    @include("ventas.proceso.VentaCabecera.BuscadorCliente")
  </div>
  @if( Illuminate\Support\Facades\Config::get("app.my_config.funciones.delivery") == "S" )
  <div class="col-12   text-light   fs-5">
    <x-pretty-checkbox name="DELIVERY" value="N" label="Forma de entrega" onValue="S" offValue="N" callback="calcularTotalesVuelto()" />
    <x-servicios-list name="SERVICIO" value="" id="" callback="calcularTotalesVuelto()" style="" class="form-select p-0 text-center  fs-5">
    </x-servicios-list>
  </div>
  @endif

</div>
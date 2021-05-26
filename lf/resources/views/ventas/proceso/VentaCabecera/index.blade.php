@include("buscador.Buscador")
 
<div id="VENTA-HEADER" class="row   m-0 m-md-0   mb-lg-0">
    <div id="VENTA-HEADER-SESION" class="col-12 p-1 m-0 pt-2 d-flex flex-row"  style=" background: var(--color-3) !important;">
  
       <label class="text-light text-center pr-1 fs-5 w-100" >Sesión N°:</label>
        <input readonly value="{{session('SESION')}}"    class="form-control p-0 text-center  fs-5" type="text">  
    

        <label class="text-light text-center pr-1 fs-5 w-100"  >Origen:</label>
        <x-origen-venta-list name="ORIGEN" value="" id="" callback="" style=""   class="form-select p-0 text-center  fs-5">
        </x-origen-venta-list>
    </div>
    <div id="VENTA-HEADER-CLIENTE" class="col-12  bg-light   ">
      @include("ventas.proceso.VentaCabecera.BuscadorCliente")
    </div>
</div> 
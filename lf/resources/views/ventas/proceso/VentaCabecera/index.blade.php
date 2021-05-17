@include("buscador.Buscador")
 
<div id="VENTA-HEADER" class="row   m-0 m-md-0   mb-lg-0">
    <div id="VENTA-HEADER-SESION" class="col-12 p-1 m-0 pt-2 d-flex flex-row"  style=" background: var(--color-3) !important;">
  
       <label class="text-light text-center pr-1 fs-4" style="width: 150px !important;">SESIÓN N°:</label>
        <input readonly value="{{session('SESION')}}"  style="width: 200px !important;" class="form-control p-0 text-center  fs-4" type="text">  
    </div>
    <div id="VENTA-HEADER-CLIENTE" class="col-12  bg-light   ">
      @include("ventas.proceso.VentaCabecera.BuscadorCliente")
    </div>
</div> 
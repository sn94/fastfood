<div class="container-fluid text-light bg-dark">
<h4 class="text-center mt-2"  >Ficha de proveedor</h4>
 <div id="loaderplace"></div>
 <input type="hidden" id="REDIRECCION" value="N">
 <form id="FORM-PROVEEDOR" action="{{url('proveedores')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardarProveedor(event)">

         @include("proveedores.form")
 </form>
</div>
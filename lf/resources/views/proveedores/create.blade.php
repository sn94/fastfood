@extends("templates.admin.index")


@section("content")



<div class="container-fluid bg-dark text-light">
         
<a class="btn btn-sm btn-warning" href="<?= url("proveedores")?>"> Lista de proveedores</a>

<h2 class="text-center mt-2"  >Ficha de proveedor</h2>

<div id="loaderplace"></div>

<input type="hidden" id="REDIRECCION" value="S">
<form id="FORM-PROVEEDOR" action="{{url('proveedores')}}"  method="POST"    onkeypress="if(event.keyCode == 13) event.preventDefault();"    onsubmit="guardarProveedor(event)">
        @include("proveedores.form")
</form>

</div>


 
@endsection
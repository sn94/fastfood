@extends("templates.admin.index")


@section("content")



<input type="hidden" id="PROVEEDORES-INDEX"  value="{{url('proveedores')}}" >


 
<a class="btn btn-sm btn-warning" href="<?= url("proveedores")?>"> Lista de proveedores</a>

<h2 class="text-center mt-2" >Ficha de proveedor</h2>


<div id="loaderplace"></div>
<input type="hidden" id="REDIRECCION" value="S">


<form id="FORM-PROVEEDOR" action="{{url('proveedores')}}"  method="POST"    onkeypress="if(event.keyCode == 13) event.preventDefault();"    onsubmit="guardarProveedor(event)">
  
        
        <input type="hidden" name="_method"  value="PUT">
        @include("proveedores.create.form")
     

</form>
 
@endsection
@extends("templates.admin.index")


@section("content")



<input type="hidden" id="CARGO-INDEX"  value="{{url('usuario')}}" >


 
<a class="btn btn-sm btn-warning" href="<?= url("usuario")?>"> Listado de usuarios</a>

<h2 class="text-center mt-2" style="font-family: titlefont;">Ficha de usuario</h2>


<div id="loaderplace"></div>
<form action="{{url('usuario')}}"  method="POST"    onkeypress="if(event.keyCode == 13) event.preventDefault();"    onsubmit="guardar(event)" enctype="multipart/form-data" >
  
        
        <input type="hidden" name="_method"  value="PUT">
        @include("usuario.form")
     

</form>

 
@endsection
@extends("templates.admin.index")


@section("content")



@php

$methodForm= "POST";
if( isset($usuario))
$methodForm= "PUT";
@endphp

<div class="container-fluid bg-dark text-light col-12 col-md-10 mt-1">
        <a class="btn btn-sm btn-warning" href="<?= url("usuario") ?>"> Listado de usuarios</a>

        <h2 class="text-center mt-2">Ficha de usuario</h2>
        <div id="loaderplace"></div>
        <form action="{{url('usuario')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)" enctype="multipart/form-data">

                <input type="hidden" name="_method" value="{{$methodForm}}">

                @include("usuario.form")
        </form>

</div>





@endsection
@extends("templates.admin.index")


@section("content")



@php

$methodForm= "POST";
if( isset($usuario))
$methodForm= "PUT";
@endphp

<div class="container-fluid fast-food-bg   col-12 col-md-6 mt-1">
       
        <h3 class="fast-food-big-title">Ficha de usuario</h3>
        <div id="loaderplace"></div>
        <form action="{{url('usuario')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)" enctype="multipart/form-data">

                <input type="hidden" name="_method" value="{{$methodForm}}">

                @include("usuario.form")
        </form>

</div>





@endsection
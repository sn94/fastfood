@extends("templates.admin.index")


@section("content")

 

<input type="hidden" id="GRILL-URL" value="{{url('cargo')}}">
<input type="hidden" id="GRILL-URL-CUSTOM" value="{{url('cargo/buscar')}}">






<div class="container mt-1 col-12 col-md-5 fast-food-bg pb-5">

    <h3 class="fast-food-big-title"  >Parámetros</h3>



    <div id="loaderplace"></div>


    <div id="form">
        @include("parametros.create")
    </div>
 
</div>

 
@endsection
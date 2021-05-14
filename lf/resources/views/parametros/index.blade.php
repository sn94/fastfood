@extends("templates.admin.index")


@section("content")


<style>
    #search {
        border-radius: 20px;
        color: whitesmoke;
        text-align: center;
        font-size: 20px;
        font-family: mainfont;
        border: none;
        background: transparent;
        margin: 5px;
        width: 100%;
        border-bottom: 3px white solid !important;
    }

    #search:focus {
        border-radius: 20px;
        border: #cace82 1px solid;
    }

    #search::placeholder {

        font-size: 20px;
        color: whitesmoke;
        font-family: mainfont;
        text-align: center;
    }
</style>

<input type="hidden" id="GRILL-URL" value="{{url('cargo')}}">
<input type="hidden" id="GRILL-URL-CUSTOM" value="{{url('cargo/buscar')}}">






<div class="container col-12 col-md-5 bg-dark pb-5">

    <h3 class="text-center text-light mt-2" >Parámetros</h3>



    <div id="loaderplace"></div>


    <div id="form">
        @include("parametros.create")
    </div>
 
</div>

 
@endsection
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






<div class="container col-12 col-md-5">
    <h3 class="text-center mt-2" style="font-family: titlefont;">Parámetros</h3>



    <div id="loaderplace"></div>


    <div id="form">
        @include("parametros.create")
    </div>
 
</div>


<script>
    function show_loader() {
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
    }

    function hide_loader() {
        $("#loaderplace").html("");
    }




     
</script>
@endsection
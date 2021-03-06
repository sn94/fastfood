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

<input type="hidden" id="GRILL-URL" value="{{url('niveles')}}">
<input type="hidden" id="GRILL-URL-CUSTOM" value="{{url('niveles/buscar')}}">






<div class="container col-12 col-md-4">
    <h3 class="fast-food-big-title"  >Ficha de Niveles de usuario</h3>



    <div id="loaderplace"></div>


    <div id="form">
        @include("niveles.create")
    </div>


    <input autocomplete="off" id="search" type="text" placeholder="BUSCAR POR DESCRIPCION" oninput="buscar( this)">

    <div id="grill" style="min-height: 300px;">

    </div>
</div>


<script>


    async function buscar(esto) {
        let termino = esto.value;
        let grill_url = $("#GRILL-URL-CUSTOM").val();
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#grill").html(loader);
        let req = await fetch(grill_url, {

            method: "POST",
            headers: {
                'X-Requested-With': "XMLHttpRequest",
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: "buscado=" + termino
        });
        let resp = await req.text();
        $("#grill").html(resp);
    }

    async function fill_grill(url_optional) {


        let grill_url = $("#GRILL-URL").val();
        if (url_optional != undefined) {
            url_optional.preventDefault();
            grill_url = url_optional.currentTarget.href;
        }

        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#grill").html(loader);
        let req = await fetch(grill_url, {

            headers: {
                'X-Requested-With': "XMLHttpRequest"
            }
        });
        let resp = await req.text();
        $("#grill").html(resp);

    }


    window.onload = function() {

        fill_grill();
    };
</script>
@endsection
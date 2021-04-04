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



<input type="hidden" id="GRILL-URL-CUSTOM1" value="{{url('deposito/M')}}">
<input type="hidden" id="GRILL-URL-CUSTOM2" value="{{url('deposito/P')}}">





<h2 class="text-center mt-2" style="font-family: titlefont;">Gestión del Depósito (productos y materia prima) </h2>


<label for="" style="font-weight: 600; color: white; "> FILTRAR POR: </label>
 <select style="height: 35px;" id="CONTEXTO" onchange="cambiar_contexto(event)">
    <option selected value="M">MATERIA PRIMA</option>
    <option value="P">PRODUCTOS</option>
</select>

<a class="btn btn-danger" id="ENTRADA1" href="{{url('deposito/compra/M')}}">REGISTRAR COMPRAS</a>
<a class="btn btn-danger" id="SALIDA1" href="{{url('deposito/salida/M')}}">REGISTRAR SALIDAS</a>

<a class="btn btn-danger d-none" id="ENTRADA2" href="{{url('deposito/compra/P')}}">REGISTRAR COMPRAS</a>
 
<a class="btn btn-danger d-none" id="SALIDA2" href="{{url('deposito/salida/P')}}">REGISTRAR SALIDAS</a>


<div id="loaderplace"></div>

<input autocomplete="off" id="search" type="text" placeholder="BUSCAR POR DESCRIPCION" oninput="buscar( this)">


<div id="grill" style="min-height: 300px;">

</div>


<script>
    function show_loader() {
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
    }

    function hide_loader() {
        $("#loaderplace").html("");
    }




    async function buscar(esto) {

        let termino = (esto == undefined) ? "" : esto.value;
        let custom_link = $("#GRILL-URL-CUSTOM1").val();

        let contexto = $("#CONTEXTO").val();

        if ($("#CONTEXTO").val() == "M") custom_link = $("#GRILL-URL-CUSTOM1").val();
        else custom_link = $("#GRILL-URL-CUSTOM2").val();

        let grill_url = custom_link;
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#grill").html(loader);
        let req = await fetch(grill_url, {

            method: "POST",
            headers: {
                'X-Requested-With': "XMLHttpRequest",
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: "buscado=" + termino + "&contexto=" + contexto
        });
        let resp = await req.text();
        $("#grill").html(resp);
    }




    function cambiar_contexto(ev) {

        if (ev.target.value == "M") {
            $("#ENTRADA1").removeClass("d-none");
            $("#SALIDA1").removeClass("d-none");
            
            $("#ENTRADA2").addClass("d-none");
            $("#SALIDA2").addClass("d-none");
        } else {
            $("#ENTRADA1").addClass("d-none");
            $("#SALIDA1").addClass("d-none");
            $("#ENTRADA2").removeClass("d-none");
            $("#SALIDA2").removeClass("d-none");
        }
        buscar();
    }





    window.onload = function() {

        buscar();
    };
</script>
@endsection
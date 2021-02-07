@extends("templates.app_admin")


@section("content")



 
<a class="btn btn-lg btn-warning" href="<?= url("productos")?>"> Lista de productos</a>

<h2 class="text-center mt-2" style="font-family: titlefont;">Nuevo Producto</h2>


<div id="loaderplace"></div>
<form action="{{url('productos')}}"  method="POST"   onsubmit="guardar(event)"  enctype="multipart/form-data">
 
        @include("mod_admin.productos.form")
     

</form>



<script>
  function show_loader() {
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
    }

    function hide_loader() {
        $("#loaderplace").html("");
    }



async function guardar(ev) {
    //config_.processData= false; config_.contentType= false;
        ev.preventDefault();  
        show_loader();
        limpiar_numero();
        let req = await fetch(ev.target.action, {
            "method": "POST",
            
            headers: {
            
               
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              
            },
            body:   new FormData(   ev.target )
        });
        let resp = await req.json();
        hide_loader();
        if ("ok" in resp) {
            alert(  resp.ok);
            reset_form(  ev.target );
            restaurar_sep_miles();
            //window.location.reload();
        } else {
          
            restaurar_sep_miles();
            alert(  resp.err);
        }


    }
    
</script>
@endsection
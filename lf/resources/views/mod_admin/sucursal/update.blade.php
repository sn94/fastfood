@extends("templates.app_admin")


@section("content")



<input type="hidden" id="sucursal-INDEX"  value="{{url('sucursal')}}" >


 
<a class="btn btn-lg btn-warning" href="<?= url("sucursal")?>"> Listado de Sucursales</a>

<h2 class="text-center mt-2" style="font-family: titlefont;">Actualizar datos de Sucursales</h2>


<div id="loaderplace"></div>
<form action="{{url('sucursal')}}"  method="POST"   onsubmit="guardar(event)" enctype="multipart/form-data" >
  
        
        <input type="hidden" name="_method"  value="PUT">
        @include("mod_admin.sucursal.form")
     

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
        ev.preventDefault(); 

        show_loader();
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
           // window.location=  $("#sucursal-INDEX").val() ;
        } else { 
          
            alert(  resp.err);
        }


    }
</script>
@endsection
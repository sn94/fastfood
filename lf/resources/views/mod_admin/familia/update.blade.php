@extends("templates.app_admin")


@section("content")



<input type="hidden" id="FAMILIA-INDEX"  value="{{url('familia')}}" >


 
<a class="btn btn-lg btn-warning" href="<?= url("familia")?>"> Listado de Familias de productos</a>

<h2 class="text-center mt-2" style="font-family: titlefont;">Actualizar datos de familia de producto</h2>


<div id="loaderplace"></div>
<form action="{{url('familia')}}"  method="POST"   onsubmit="guardar(event)" enctype="multipart/form-data" >
  
        
        <input type="hidden" name="_method"  value="PUT">
        @include("mod_admin.familia.form")
     

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
           // window.location=  $("#familia-INDEX").val() ;
        } else { 
          
            alert(  resp.err);
        }


    }
</script>
@endsection
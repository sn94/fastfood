@extends("templates.admin.index")


@section("content")



<input type="hidden" id="PRODUCTOS-INDEX" value="{{url('stock')}}">

 
<div id="loaderplace"></div>
<input type="hidden" id="REDIRECCIONAR" value="SI">
<form id="STOCKFORM" action="{{url('stock')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardarStock(event)" enctype="multipart/form-data">

    <input type="hidden" name="_method" value="PUT">

 <div class="container-fluid bg-dark">
 <div class="row bg-dark">
        @if( ! request()->ajax())
        <div class="col-12 col-md-6">
            @include("stock.botones")
        </div>
        @endif
        <div class="col-12 col-md-6">
            <h3 class="text-center " style="font-family: titlefont;">Ficha de Stock</h3>
        </div>
    </div>


    @include("stock.forms.index")
 </div>
</form>


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
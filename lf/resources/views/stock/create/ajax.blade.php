

<div id="loaderplace"></div>

@if( request()->ajax())
<input type="hidden" id="REDIRECCIONAR" value="NO">
@else
<input type="hidden" id="REDIRECCIONAR" value="SI">
@endif

<form class="bg-dark text-light" id="STOCKFORM" action="{{url('stock')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardarStock(event)" enctype="multipart/form-data">


@if( isset($stock) )
<input type="hidden" name="_method" value="PUT">
@else 
<input type="hidden" name="_method" value="POST">
@endif


    <div class="row">
        @if( ! request()->ajax())
        <div class="col-12 col-md-4">
            @include("stock.botones")
        </div>
        @endif
        <div class="col-12 col-md-4">
            <h3 class="text-center " style="font-family: titlefont;">Ficha de Stock</h3>
        </div>
    </div>
    @include("stock.forms.index")
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
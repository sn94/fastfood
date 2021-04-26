<div id="loaderplace"></div>

@if( request()->ajax())
<input type="hidden" id="REDIRECCIONAR" value="NO">
@else
<input type="hidden" id="REDIRECCIONAR" value="SI">
@endif

<form class="bg-dark text-light  border border-success border-1 rounded" id="STOCKFORM" action="{{url('stock')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardarStock(event)" enctype="multipart/form-data">


    @if( isset($stock) )
    <input type="hidden" name="REGNRO" value="{{$stock->REGNRO}}">

    @method("PUT")
    @else
    <input type="hidden" name="_method" value="POST">
    @endif


    <div class="row">


        <!--Botones -->
        <div class="col-12 col-md-4 ">
            <div class="row mb-0   pt-1 pb-0  pr-1 pl-1">
                <div class="col-12  col-md-4 pb-1">
                    <div class="btn-group">
                        @if( ! request()->ajax())
                        <a class="btn btn-sm  btn-secondary mr-2" href="<?= url("stock") ?>"> VOLVER </a>
                        <a class="btn btn-secondary btn-sm" href="{{url('stock/create')}}">NUEVO</a>
                        @endif
                        <button type="submit" class="btn btn-warning btn-sm">GUARDAR</button>

                    </div>
                </div>
                <div class="col-12 offset-md-7 col-md-1 pb-1"></div>
            </div>
        </div>
        <!--End Botones -->

        <div class="col-12 col-md-4">
            <h3 class="text-center ">Ficha de Stock</h3>
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
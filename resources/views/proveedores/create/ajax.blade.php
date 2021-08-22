<div class="container-fluid  ">

    
        <h3 class="fast-food-big-title">Ficha de proveedor</h3>

        <div id="loaderplace"></div>

        @if( request()->ajax() )
        <input type="hidden" id="REDIRECCION" value="N">
        @else
        <input type="hidden" id="REDIRECCION" value="S">
        @endif

        <form id="FORM-PROVEEDOR" action="{{url('proveedores')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardarProveedor(event)">



                @if( isset($proveedores) )
                <input type="hidden" name="_method" value="PUT">
                @else
                <input type="hidden" name="_method" value="POST">
                @endif


                @include("proveedores.create.form")
        </form>
</div>
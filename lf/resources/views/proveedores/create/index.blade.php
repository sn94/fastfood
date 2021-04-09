@if(  request()->ajax())

        @include("proveedores.create.ajax")

@else

        @extends("templates.admin.index")
        @section("content")
        <div class="container-fluid col-12 col-md-8 bg-dark text-light mt-2">
                @include("proveedores.create.ajax")
        </div>
        @endsection

@endif
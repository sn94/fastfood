
@if(request()->ajax())
<div class="container col-12 col-md-4">

<h4 class="text-center">Ficha de turnos</h4>
<form action="{{url('turno')}}"  method="POST"    onkeypress="if(event.keyCode == 13) event.preventDefault();"    onsubmit="guardar(event)"  enctype="multipart/form-data">
        @include("turno.form")
</form>
 
</div>
@else 
<form action="{{url('turno')}}"  method="POST"    onkeypress="if(event.keyCode == 13) event.preventDefault();"    onsubmit="guardar(event)"  enctype="multipart/form-data">
        @include("turno.form")
</form>
 

@endif


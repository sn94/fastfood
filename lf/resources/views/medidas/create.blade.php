 
<form action="{{url('medidas')}}"  method="POST"    onkeypress="if(event.keyCode == 13) event.preventDefault();"    onsubmit="guardar(event)"  enctype="multipart/form-data">
 
        @include("medidas.form")
     

</form>
 
 
<form action="{{url('ciudades')}}"  method="POST"    onkeypress="if(event.keyCode == 13) event.preventDefault();"    onsubmit="guardar(event)"  enctype="multipart/form-data">
 
        @include("ciudades.form")
     

</form>
 
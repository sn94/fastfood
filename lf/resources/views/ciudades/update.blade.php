 <form action="{{url('ciudades')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)" enctype="multipart/form-data">
     <input type="hidden" name="_method" value="PUT">
     @include("ciudades.form")


 </form>
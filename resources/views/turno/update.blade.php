 <form action="{{url('turno')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)" enctype="multipart/form-data">
     <input type="hidden" name="_method" value="PUT">
     @include("turno.form")


 </form>
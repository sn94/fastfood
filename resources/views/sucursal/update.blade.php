 
 <form action="{{url('sucursal')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)" >


     <input type="hidden" name="_method" value="PUT">
     @include("sucursal.form")


 </form>
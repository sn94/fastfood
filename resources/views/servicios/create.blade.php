 <form action="{{url('servicios')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)">


         @php
         $metodo= isset(  $servicio ) ? "PUT" : "POST";
         @endphp
         <input type="hidden" name="_method" value="{{$metodo}}">
      
         @include("servicios.form")

 </form>
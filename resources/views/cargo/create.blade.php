 <form action="{{url('cargo')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)">


         @php
         $metodo= isset(  $cargo ) ? "PUT" : "POST";
         @endphp
         <input type="hidden" name="_method" value="{{$metodo}}">
      
         @include("cargo.form")

 </form>
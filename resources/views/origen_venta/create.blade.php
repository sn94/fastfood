 <form action="{{url('origen-venta')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)">


         @php
         $metodo= isset(  $origen_venta ) ? "PUT" : "POST";
         @endphp
         <input type="hidden" name="_method" value="{{$metodo}}">
      
         @include("origen_venta.form")

 </form>
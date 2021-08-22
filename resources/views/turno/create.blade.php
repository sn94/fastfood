<div class="container">

        @if(request()->ajax())
        <h4 class="text-center">Ficha de turnos</h4>
        @endif
        <form action="{{url('turno')}}" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardar(event)">


                @php
                $metodo= isset( $turno ) ? "PUT" : "POST";
                @endphp
                <input type="hidden" name="_method" value="{{$metodo}}">
                @include("turno.form")
        </form>

</div>
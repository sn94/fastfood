<div>
    <!-- Happiness is not something readymade. It comes from your own actions. - Dalai Lama -->

    <?php

    use App\Models\Ciudades_departa;

    $ciudades = Ciudades_departa::orderBy("regnro")->get();

    $onChange= isset(  $onChange) ?  $onChange : "";
    ?>

    <select class="{{$clase}}" name="{{$name}}" style="{{$style}}" value="{{$value}}" onChange="{{$onChange}}">
    <option value="">ELEGIR...</option>
        @foreach( $ciudades as $ciu)

        <optgroup label="{{$ciu->departa}}">
            @php
            $cius= $ciu->ciudades;
            @endphp
            @foreach( $cius as $city)
            @if( $city->regnro == $value)
            <option selected value="{{$city->regnro}}"> {{$city->ciudad}} </option>
            @else
            <option value="{{$city->regnro}}"> {{$city->ciudad}} </option>
            @endif
            @endforeach
        </optgroup>
        @endforeach
    </select>

</div>
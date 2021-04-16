<?php


//Id dinamico
$idDinamico = $name . random_int(100, 999);
$mostrarOn = $checked == "true" ? "" : "d-none";
$mostrarOff = $checked == "true" ? "d-none" : "";
$checkar =  $checked == "true" ?  "checked" : "";

//icon size
$iconSize= "width: {$size}px;height: {$size}px;";
?>


<div class="form-check p-0">
    <input style="display: none;" onchange="<?= $idDinamico ?>onchange(event)" id="{{$idDinamico}}" type="radio" name="{{$name}}" value="{{$value}}" {{$checkar}}>

    <img style="{{$iconSize}}"  class="{{$mostrarOn}}" id="{{$idDinamico.'-on'}}" onclick="<?= $idDinamico ?>prettyRadioButtonAction('off')" src="{{url('assets/icons/radio_on.png')}}" alt="">

    <img style="{{$iconSize}}"  class="{{$mostrarOff}}" id="{{$idDinamico.'-off'}}" onclick="<?= $idDinamico ?>prettyRadioButtonAction('on')" src="{{url('assets/icons/radio_off.png')}}" alt="">


    <label class="form-check-label" for="exampleRadios1">
        {{$label}}

        {{$slot}}
    </label>
</div>

<script>
    function <?= $idDinamico ?>onchange( esto) {

          
        if ( esto.checked) {
            //turn on
            $("#<?= $idDinamico ?>-on").removeClass("d-none");
            $("#<?= $idDinamico ?>-off").addClass("d-none");
        } else {
            $("#<?= $idDinamico ?>-on").addClass("d-none");
            $("#<?= $idDinamico ?>-off").removeClass("d-none");
        }

       
    }

    function <?= $idDinamico ?>prettyRadioButtonAction(whatToDo) {

        if (whatToDo == "on") { 

            //DESTILDAR OTROS
            let otros_nodos = document.querySelectorAll("input[name=<?= $name ?>]");
          
            Array.prototype.forEach.call(otros_nodos, (radio) => { 
                radio.checked = false;
                radio.onchange( radio);
            });

            
            let radioButtonActual=  document.getElementById("<?= $idDinamico ?>");
            radioButtonActual.checked= true;
            radioButtonActual.onchange(  radioButtonActual);

        }  

    }
</script>
<div>
    <!-- Very little is needed to make a happy life. - Marcus Antoninus -->
@php 

//field id 
$idUnico=  $id== "" ?  ($name."_".rand(10,1000))  :  $id;
@endphp


    <script>
        function <?=$idUnico?>changeCheckbox() {
            if ($('#<?=$idUnico?>').val() == '<?=$onValue?>') {
                $('#<?=$idUnico?>').val('<?=$offValue?>');
                $("#<?=$idUnico?>checkbox .ON").addClass("d-none");
                $("#<?=$idUnico?>checkbox .OFF").removeClass("d-none");
            } else {
                $('#<?=$idUnico?>').val('<?=$onValue?>');
                $("#<?=$idUnico?>checkbox .ON").removeClass("d-none");
                $("#<?=$idUnico?>checkbox .OFF").addClass("d-none");
            }
            <?=$callback == '' ?  ''  :  $callback?>
        }
    </script>

    <div id="<?=$idUnico?>checkbox" onclick="<?=$idUnico?>changeCheckbox()" style="display: flex;flex-direction: row; align-items: center;height: 32px;">
        <input type="hidden" id="<?=$idUnico?>" name="{{$name}}" value="{{$value}}">
        @php
        $on= $value == $onValue ? "" : "d-none";
        $off= $value == $offValue ? "" : "d-none";

        @endphp
        <label for="">{{$label}}</label>
        <img class="ON {{$on}}" src="{{url('assets/icons/on.png')}}">
        <img class="OFF {{$off}}" src="{{url('assets/icons/off.png')}}">
    </div>
</div>
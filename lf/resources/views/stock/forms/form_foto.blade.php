@php
$IMG= isset( $stock )? $stock->IMG : "";
@endphp


<div class="d-flex flex-row pt-1">
    <label style="width: 250px;">URL DE IMAGEN (OPCIONAL):</label>
    <input type="text" name="IMG_EXT_URL" class="form-control">
</div>

<div class="input-group mb-3">

    <div class="custom-file">
        <input type="file" name="IMG" onchange="show_loaded_image(event)" class="custom-file-input" id="inputGroupFile01">

        <label class="custom-file-label" for="inputGroupFile01">Cargue una imagen</label>
    </div>
</div>
<div id="IMG" style="display: flex; justify-content: center;">
    <img id="STOCKIMG" style="height: 200px; width: 200px;" class="img-fluid" src="{{url($IMG)}}" alt="">
</div>
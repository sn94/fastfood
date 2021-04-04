


<input type="hidden" name="REGISTRADO_POR" value="<?= session("ID") ?>">
<input type="hidden" name="SUCURSAL" value="<?= session("SUCURSAL") ?>">
<input type="hidden" name="PRODUCCION_ID" value="{{$PRODUCCION_ID}}">
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">


<div class="row bg-dark">
    <div class="col-12 col-md-2  mb-1">
        <button type="submit" class="btn btn-danger"> GUARDAR</button>
    </div>
</div>


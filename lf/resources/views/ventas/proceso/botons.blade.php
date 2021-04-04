<div class="bg-dark" style="width: 100%;display: grid; grid-template-columns: 35% 35% 30%;">
  <button onclick="nuevaVenta()"   type="button" class="btn btn-secondary ">NUEVO</button>

  <button   onclick="definirDetallesPago();" type="button" class="btn btn-success">PAGAR</button>

  <button   onclick="cancelar();" type="button" class="btn btn-danger">SALIR</button>

</div>


<script>
  function cancelar() {

    if (confirm("Seguro que desea cancelar esta venta?"))
      window.location = "<?= url('modulo-caja') ?>";
  }



  function definirDetallesPago() {

    //   if($('#FORMAPAGO').val() != 'EFECTIVO' )

    $('#FORMAS-DE-PAGO').modal('show');
    //guardar();
  }
</script>
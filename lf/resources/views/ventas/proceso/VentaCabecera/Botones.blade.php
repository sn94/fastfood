<div style="width: 100%;display: grid; grid-template-columns: 35% 35% 30%;">
  <button onclick="nuevaVenta()" type="button" class="btn btn-secondary ">NUEVO</button>

  <button onclick="definirDetallesPago();" type="button" class="btn btn-success">PAGAR</button>

  <button onclick="cancelar();" type="button" class="btn btn-danger">SALIR</button>

</div>


<script>
  function nuevaVenta() {
    if ("ultimoIdVentaRegistrado" in window)
      ultimoIdVentaRegistrado = undefined;
    //limpiar tabla
    $("#grill-ticket").html("");
    //cliente default

    $("#CLIENTE-KEY").val("");
    $("#CLIENTE-NAME").val("");

    //limpiar campos de totales 
    $("#RESUMEN input").val("0");
    $("#TOTAL-VENTA").val("0");
    $("#IMPORTE_PAGO").val("0");
    $("#VUELTO").val("0");
    //limpiar datos de forma de pago
    $("#FORMAS-DE-PAGO input").val("");
    //limpiar ultimo numero de ticket generado
    $("#TICKET-NUMERO").val("");
    $("#TICKET-DATA-PANEL").addClass("d-none");
    ventas_model = [];
  }


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
<style>
    /** Estilos generales  */
    table thead tr th,
    table tbody tr td,
    table tfoot tr td {
        padding: 0px !important;
        padding-left: 1px !important;
        padding-right: 1px;
    }

table {
        font-size: 10px;
        font-family: Arial, Helvetica, sans-serif;
    }
    table thead tr th {
        border-bottom: 1px solid black;
    }



    .header {
        position: fixed;
        top: -1cm;
        left: 0cm;
        right: 0cm;
        height: 2cm;
        font-size: 9px;
    }
    .text-end {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }
</style>

<p class="header">
    {{'Fecha de impresión: '.date("d/m/Y H:i")}}
</p>
 
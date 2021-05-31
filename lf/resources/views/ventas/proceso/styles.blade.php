<style>


 

.nav-tabs {
  background-color: var(--color-secundario) !important;
}

   .nav-tabs .nav-item.show .nav-link,
   .nav-tabs .nav-link.active {
     color: #ff0000 !important;
     background-color: white !important;
   }

   .nav-tabs .nav-link {
     color: white !important;
     background-color:  var(--color-secundario)  !important;
     border: 1px solid  !important;
   }

   .nav-link {
     color: var(--color-primario) !important;
     font-weight: 600 !important;
   }

   .nav-tabs .nav-link:focus,
   .nav-tabs .nav-link:hover {
     background: #2d2928 !important;
     color: white !important;
   }



  


  /**Ventana de precios **/
 
 
#VENTAS-VARIEDAD-PRECIOS { 
  background-color: #ff5131 !important;
}

#VENTAS-VARIEDAD-PRECIOS thead th {
  background: var(--color-primario) !important;
  color: white !important;
  text-align: center;
}

 
#VENTAS-VARIEDAD-PRECIOS td button {
  background: var(--color-primario) !important;
  font-size: 2rem !important;
  color: #fff !important;
  border: none;
}

#VENTAS-VARIEDAD-PRECIOS tbody tr th {
  font-size: 2rem !important;
  color: #221111 !important;
}

#VENTAS-VARIEDAD-PRECIOS thead th {
  font-size: 2rem !important;
}


 

 /**Cada item **/
 
    .FOOD-CELL {
        display: flex;
        width: 125px;
        flex-direction: column;
        background-color:  var(--color-6) !important ;
       
    }
    .FOOD-CELL.resaltado{
        border-bottom: 5px solid  var(--color-secundario) !important;
       
    }
    .FOOD-CELL.resaltado::before{
        content: '';
        position: absolute;
        border-bottom: 5px solid  var(--color-secundario) !important;
        background-color:  var(--color-secundario);
       
    }
    .FOOD-CELL:hover,  .FOOD-CELL:active  { 
        transform: scale(1.3) translateX(10px);
        position: relative;
        z-index: 10000000;
        background-color: var(--color-secundario) !important;
        border: 2px solid var(--color-secundario);
        box-shadow: 0 0 0px 10px var(--color-secundario),  0 0 10px var(--color-secundario);
    }
    .FOOD-CELL img.img-thumbnail {
        width: 95px;
        height: 95px;
    }

    .FOOD-CELL .descripcion {
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 11.3px !important;
        font-weight: 600;
        text-transform: uppercase;
        text-align: center;
        word-break: keep-all;
    }

    .FOOD-CELL .btn-normal-price {
        
        background-color:  var(--color-1) !important;
        color: black;
        font-weight: 600;
        position: absolute;
    }

    .FOOD-CELL .btn-special-price {
        background-color:  var(--color-secundario) !important;
        color: white;
        position: absolute;
    } 


    /**Resumen de venta **/ 
 
    #RESUMEN {
        z-index: 100000000;
        position: absolute;
        width: 100%;
        height: 100%;
    }

    #RESUMEN div.container {

        display: flex;
        flex-direction: column;
        background: #fae9e8;
        border: #ff5131 4px solid;
    }



    #RESUMEN label {
        color: black;
        font-family: titlefont;
        font-weight: 600;
        text-align: center;
        font-size: 14px;
        width: 100% !important;
    }



    #RESUMEN input {
        background-color: #fae9e8;
        border: none;
        text-align: right;
        font-size: 14px;
        font-family: titlefont;
        width: 100%;
    }

    #RESUMEN button.cierra {
        background-color: black;
        color: yellow;
        border-radius: 50px;
        padding: 5px 10px !important;
    }


    #RESUMEN-PAGO-TARJETA,
    #RESUMEN-PAGO-GIRO {

        display: flex;
        flex-direction: row;
    }

    #RESUMEN-PAGO-TARJETA label,
    #RESUMEN-PAGO-TARJETA input,
    #RESUMEN-PAGO-GIRO label,
    #RESUMEN-PAGO-GIRO input,
    #RESUMEN-FORMAPAGO label,
    #RESUMEN-FORMAPAGO input {
        font-size: 11.5px !important;
        height: 25px;
        font-family: Arial, Helvetica, sans-serif;
    } 


    /**Formas de pago **/
     #FORMAS-DE-PAGO fieldset label {
        font-size: 12px;
        color: white !important;
    }

    #FORMAS-DE-PAGO fieldset legend {
        font-size: 14px;
        background-color:  var(--color-secundario) !important;
        color: white;
        border-bottom: 3px  white solid;

        text-align: center;
    }

    #FORMAS-DE-PAGO fieldset {
        border: none;
        padding: 2px;
        height: 100%;
    }
    #FORMAS-DE-PAGO fieldset:hover , #FORMAS-DE-PAGO fieldset:active {
        box-shadow: 0 0 0 10px var(--color-primario-oscuro) !important;
    }

    #FORMAS-DE-PAGO fieldset input {
        height: 29px !important;
    }

    #FORMAS-DE-PAGO fieldset label {
        width: 100% !important;
    }

    #FORMAS-DE-PAGO .modal-content{
      background-color: var(--color-secundario) !important;
    }


    .VENTA-HEADER{
      background: var(--color-secundario) !important;
    }
</style>
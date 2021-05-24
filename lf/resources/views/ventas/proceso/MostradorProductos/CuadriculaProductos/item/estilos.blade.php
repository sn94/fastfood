<style>
  
    .FOOD-CELL {
        display: flex;
        width: 125px;
        flex-direction: column;
        background-color:  var(--color-6) !important ;
       
    }
    .FOOD-CELL.resaltado{
        border-bottom: 5px solid  var(--color-3) !important;
       
    }
    .FOOD-CELL.resaltado::before{
        content: '';
        position: absolute;
        border-bottom: 5px solid  var(--color-3) !important;
        background-color:  var(--color-3);
       
    }
    .FOOD-CELL:hover,  .FOOD-CELL:active  { 
        transform: scale(1.3) translateX(10px);
        position: relative;
        z-index: 10000000;
        background-color: var(--color-3) !important;
        border: 2px solid var(--color-3);
        box-shadow: 0 0 0px 10px var(--color-3),  0 0 10px var(--color-3);
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
        background-color:  var(--color-3) !important;
        color: white;
        position: absolute;
    }
</style>
<style>
    .FOOD-CELL {
        display: flex;
        width: 100px;
        flex-direction: column;
        background-color:  var(--color-6) !important ;
    }

    .FOOD-CELL:hover { 
        background-color: var(--color-1) !important;
        border: 2px solid var(--color-1);
        box-shadow: 0 0 5px var(--color-1),  0 0 10px var(--color-1);
    }
    .FOOD-CELL img.img-thumbnail {
        width: 95px;
        height: 95px;
    }

    .FOOD-CELL .descripcion {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11.5px !important;
        font-weight: 600;
        text-transform: uppercase;
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
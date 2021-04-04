<style>
    #mymodal {

        position: absolute;
        z-index: 1000;
        background-color: #000000f5;
        height: 100%;
        width: 100%;
    }


    #mymodal .mymodal-close button {
        border-radius: 20px;
        background-color: red;
        color: white;
        padding: 5 px;
    }
</style>
<div id="mymodal" class="container-fluid p-1 p-md-5 m-0 d-none">
    <div class="mymodal-close">
        <button onclick="cerrarMyModal()" type="button">X</button>
    </div>

    <div class="container-fluid content m-0">

    </div>

</div>

<script>
    window.myModalCustomHandler = undefined;

    function cerrarMyModal() {
        $('#mymodal content').html('');
        $('#mymodal').addClass('d-none');
        if (myModalCustomHandler != undefined && typeof myModalCustomHandler == "function")
            myModalCustomHandler();
        myModalCustomHandler = undefined;
    }
</script>
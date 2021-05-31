<style>
    #<?=$id?> label {
        font-weight: 600;
        color: black;

    }
</style>
<div id="{{$id}}" class="modal  " tabindex="-1" role="dialog"  style="max-width: 100%;">
    <div class="modal-dialog {{$size}}" role="document">
        <div class="modal-content fast-food-table">
            <div class="modal-header">
                <h5 class="modal-title text-dark">{{$title}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-1 text-dark">

               {{$slot}}

            </div>
            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>
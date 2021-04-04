<style>
    #<?=$id?> label {
        font-weight: 600;
        color: black;

    }
</style>
<div id="{{$id}}" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">{{$title}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

               {{$slot}}

            </div>
            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>
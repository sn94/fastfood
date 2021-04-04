 
<form id="FAMILIAFORM" action="{{url('familia')}}"  method="POST"    onkeypress="if(event.keyCode == 13) event.preventDefault();"    onsubmit="guardar(event)"   >
 
        @include("familia.form")
     

</form>
 
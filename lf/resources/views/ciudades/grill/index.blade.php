<style>
    table thead tr th, table tbody tr td{
padding: 0px !important;
padding-left: 2px !important;
padding-right: 2px;
    }
</style>
<div class="table-responsive"   style="height: 350px;">
<table class="table bg-warning bg-gradient    text-dark">

    <thead style="font-family: mainfont;font-size: 18px;">
        <tr>
            <th></th>
            <th></th>
            <th>ID</th>
            <th>DEPART.</th>
            <th>CIUDAD</th>
        </tr>
    </thead>

    <tbody>

        @foreach( $ciudades as $prov)
        <tr>
            <td>
                <a  onclick="edit_row(event)" style="color: black;" href="{{url('ciudades/update').'/'.$prov->regnro}}"> <i class="fas fa-edit"></i></a>
            </td>
            <td>
                <a onclick="delete_row(event)" style="color: black;" href="{{url('ciudades').'/'.$prov->regnro}}"> <i class="fa fa-trash"></i></a>
            </td>


            <td>{{$prov->regnro}}</td>
            <td>{{$prov->departamento->departa}}</td>
            <td>{{$prov->ciudad}}</td>


        </tr>
        @endforeach
    </tbody>

</table>

</div>
 


<script>
    async function edit_row(ev) {
        ev.preventDefault();

        let req = await fetch(ev.currentTarget.href);
        let resp = await req.text();
        $("#form").html(resp);

    }
    async function delete_row(ev) {
        ev.preventDefault();
        if (!confirm("continuar?")) return;
        let req = await fetch(ev.currentTarget.href, {
            "method": "DELETE",
            headers: {

                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: "_method=DELETE&_token=" + $('meta[name="csrf-token"]').attr('content')

        });
        let resp = await req.json();
        if ("ok" in resp) fill_grill();
        else alert(resp.err);

    }
</script>
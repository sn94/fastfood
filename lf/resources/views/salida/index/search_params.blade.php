<div style="display: flex;flex-direction: row;  justify-content: space-between;">



    <div style="display: flex;flex-direction: row;justify-content: flex-end;align-items: baseline;">
        <label style="font-weight: 600; color: white;">POR DESTINO:</label>
        @php
        $tipos_destino= [ "COCINA" => "COCINA", "SUCURSAL"=> "SUCURSAL" ];
        @endphp
        <select class="form-control" name="DESTINO" onchange="cambiar_destino(event)">
            @foreach( $tipos_destino as $tkey=> $tval)
            <option value="{{$tkey}}"> {{$tval}}</option>
            @endforeach
        </select>
    </div>

    <div style="display: flex;flex-direction: row;justify-content: flex-end;align-items: baseline;">
        <label style="font-weight: 600; color: white;">FECHAS:</label>
        <input type="date" id="FECHA-DESDE">
        <input type="date" id="FECHA-HASTA">
    </div>


    <div style="display: flex;flex-direction: row;justify-content: flex-end;">
        <a onclick="dataSearcher.formatoPdf(event)" class="btn btn-sm btn-warning" href="#"><img src="{{url('assets/icons/download.png')}}" />PDF</a>
        <a onclick="dataSearcher.formatoExcel(event);" class="btn btn-sm btn-warning" href="#"><img src="{{url('assets/icons/download.png')}}" />EXCEL</a>
    </div>
</div>
<input id="search" type="text" placeholder="BUSCAR POR CÉDULA O RUC, O NOMBRE" oninput="buscarClientes()">
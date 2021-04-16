

 <div class="row">

     <div class="col-12 col-md-6 col-lg-6">
        
             <label class="mt-1" for="element_7">TIPO: </label>
             @php
             $tipos_item= [ "PE"=>"PRODUCTO ELABORADO" , "MP" => "MATERIA PRIMA", "PP"=> "PROD. VENTA", "AF"=> "MOBILIARIO Y OTROS"];
             @endphp
             <select class="form-control" name="TIPO">
                 @foreach( $tipos_item as $tkey=> $tval)
                 @if( $TIPO == $tkey)
                 <option selected value="{{$tkey}}"> {{$tval}}</option>
                 @else
                 <option value="{{$tkey}}"> {{$tval}}</option>
                 @endif
                 @endforeach
             </select>
       

    
             <label>CÓDIGO:</label>
             <input name="CODIGO" class="form-control" type="text" maxlength="15" value="{{$CODIGO}}" />
       
 
             <label>CÓDIGO DE BARRAS: </label>
             <input name="BARCODE" class="form-control" type="text" maxlength="15" value="{{$BARCODE}}" />
         
     </div>

     <div class="col-12 col-md-6 col-lg-6"> 
             <label>DESCRIPCIÓN: </label>
             <input required name="DESCRIPCION" class="form-control" type="text" maxlength="80" value="{{$DESCRIPCION}}" />
 
             <label>DESCR. P/TICKET: </label>
             <input required name="DESCR_CORTA" class="form-control" type="text" maxlength="25" value="{{$DESCR_CORTA}}" />
 
        
             <label>IVA: </label>
             <select name="TRIBUTO" class="form-control">
                 @php
                 // 0=> "EXENTA",
                 $option_iva= [ 10=> "10 %", 5=> "5 %" ];
                 @endphp

                 @foreach( $option_iva as $num => $val)
                 @if( $num == $IVA)
                 <option selected value="{{$num}}"> {{$val}} </option>
                 @else
                 <option value="{{$num}}"> {{$val}} </option>
                 @endif
                 @endforeach
             </select>
      
 
           
        
     </div>
 </div>
 <style>
     h4 {
         text-align: center;
     }

     table {

         font-family: Arial, Helvetica, sans-serif;
     }

     table thead tr th,
     table tbody tr td {
         padding: 0px;

     }



     table thead tr th {
         font-size: 11px;
         border-bottom: 1px solid black;
     }

     table tbody tr td,
     table tbody tr th {
         font-size: 10px;
     }
 </style>



 <h4>Proveedores</h4>
 <table>

     <thead>

         <tr>
             <th>CÉDULA/RUC</th>
             <th>RAZÓN SOCIAL</th>
             <th>DOMICILIO</th>
             <th>CIUDAD</th>
             <th>TELÉF.</th>
             <th>CELULAR</th>
         </tr>
     </thead>

     <tbody>

         @foreach( $datalist as $prov)
         <tr>

             <td>{{$prov->CEDULA_RUC}}</td>
             <td>{{$prov->NOMBRE}}</td>
             <td>{{$prov->DIRECCION}}</td>
             <td>{{ is_null( $prov->ciudad_) ? "" :  $prov->ciudad_->ciudad}}</td>
             <td>{{$prov->TELEFONO}}</td>
             <td> {{$prov->CELULAR}}</td>

         </tr>
         @endforeach
     </tbody>

 </table>
<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Clientes;
use App\Models\Productos;
use App\Models\Ventas;
use App\Models\Ventas_det;
use Exception;
use Illuminate\Support\Facades\DB;

class VentasController extends Controller {

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index( )
    {
 //Pantalla de registro de venta: Seleccion de productos
      
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET")
       { $productos= Productos::get(); 
        return view('mod_caja.ventas.index' ,   ['productos' =>   $productos]);}
          
        else {
 
             
            
            try{
                DB::beginTransaction();
                //Cabecera
                $nventa =  new Ventas();
                $nventa->fill(request()->input());
                $nventa->save();


                //Detalle
                
                $ITEM=  request()->input("ITEM");
                $CANTIDAD=  request()->input("CANTIDAD");
                $PRECIO=  request()->input("PRECIO");
                for(  $numero= 0; $numero  <  sizeof($ITEM);  $numero++ ):

                    $r1= $ITEM[$numero];
                    $r2= $CANTIDAD[$numero];
                    $r3= $PRECIO[ $numero ];
                    $p_exe=  0; $p_10= 0;  $p_5= 0;
                    
                    $TRIBUTO= (Productos::find( $ITEM[$numero]))->TRIBUTO;
                    if( $TRIBUTO  ==  "0"  ) 
                     {$p_exe=  $r3 *    $r2;  $p_10= 0;  $p_5=0;}
                     if( $TRIBUTO  ==  "10"  ) 
                     {$p_10=  $r3 *    $r2;  $p_exe= 0;  $p_5=0;}

                     if( $TRIBUTO  ==  "5"  ) 
                     {$p_5=  $r3 *    $r2;  $p_10= 0;  $p_exe=0;}
                    $sql=     
                     ['VENTA_ID'=> $nventa->REGNRO, 'ITEM'=> $r1,'CANTIDAD'=> $r2 ,'P_UNITARIO'=> $r3,'EXENTA'=> $p_exe,'TOT5' => $p_5,  'TOT10' => $p_10  ];
                     (new Ventas_det())->fill( $sql)->save();
                endfor;
               

                DB::commit();
                return response()->json(  ['ok'=>  "Venta registrada"] );
            }catch( Exception  $ex){
                DB::rollBack();
                return response()->json(  ['err'=>   $ex->getMessage()  ] );      
            }
        
        }
    }


    
    public function update(  $id= NULL)
    {
        if (request()->getMethod()  ==  "GET")
         { 
             $cli=  Clientes::find(  $id );
               return view('mod_caja.clientes.update',  ['cliente'=>  $cli ]);
            }
        else {

           
            try{
                $id_= request()->input("REGNRO");
                $nuevo_cliente =  Clientes::find(   $id_);


                if(  $nuevo_cliente->CEDULA_RUC !=  request()->input("CEDULA_RUC")  &&  $this->ruc_cedula_registrado(  request()->input("CEDULA_RUC")  ))
                return response()->json(  ['err'=>  "CÉDULA/RUC ya existe"] );


                $nuevo_cliente->fill(request()->input());
                $nuevo_cliente->save();
                return response()->json(  ['ok'=>  "Cliente Actualizado"] );
            }catch( Exception  $ex){
                return response()->json(  ['err'=>   $ex->getMessage()  ] );      
            }
        
        }
    }



    public function delete(  $id= NULL){
        $reg= Clientes::find(  $id) ;
        if(  !is_null(  $reg) )  { 
            $reg->delete();
            return response()->json(  ['ok'=>  "Cliente eliminado"] );
        }else{
            return response()->json(  ['err'=>  "ID inexistente" ] );   
        }
    }




    //VALIDACIONES


    private function  ruc_cedula_registrado($arg)
    {

        $cli =  Clientes::where("CEDULA_RUC",  $arg)->first();
        return  !is_null($cli);
    }
}
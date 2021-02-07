<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Proveedores;
use Exception;

class ProveedoresController extends Controller {

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index( )
    {


        $buscado= "";
        if(  request()->method() ==  "POST")  $buscado=  request()->input("buscado");

        $proveedores= Proveedores::orderBy("created_at");
        if(  $buscado !=  ""){
            $proveedores=  $proveedores
            ->whereRaw(" CEDULA_RUC LIKE '%$buscado%' or  NOMBRE LIKE '%$buscado%'  ")  ; 
        }

        //El formato de los datos
        $formato=  request()->header("formato");

        //Si es JSON retornar
        if ($formato == "json") {
            $proveedores =  $proveedores->get();
            return response()->json($proveedores);
        }
         
        $resultados=  $proveedores->paginate(10);
        if( request()->ajax())
        return view('mod_admin.proveedores.grill',  ['proveedores'=>  $resultados]);
        else
        return view('mod_admin.proveedores.index');
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET")
            return view('mod_admin.proveedores.create');
        else {

            if(  $this->ruc_cedula_registrado(  request()->input("CEDULA_RUC")  ))
                return response()->json(  ['err'=>  "CÉDULA/RUC ya existe"] );
            
            try{
                $nuevo_cliente =  new Proveedores();
                $nuevo_cliente->fill(request()->input());
                $nuevo_cliente->save();
                return response()->json(  ['ok'=>  "Proveedor registrado"] );
            }catch( Exception  $ex){
                return response()->json(  ['err'=>   $ex->getMessage()  ] );      
            }
        
        }
    }


    
    public function update(  $id= NULL)
    {
        if (request()->getMethod()  ==  "GET")
         { 
             $cli=  Proveedores::find(  $id );
               return view('mod_admin.proveedores.update',  ['cliente'=>  $cli ]);
            }
        else {

           
            try{
                $id_= request()->input("REGNRO");
                $nuevo_cliente =  Proveedores::find(   $id_);


                if(  $nuevo_cliente->CEDULA_RUC !=  request()->input("CEDULA_RUC")  &&  $this->ruc_cedula_registrado(  request()->input("CEDULA_RUC")  ))
                return response()->json(  ['err'=>  "CÉDULA/RUC ya existe"] );


                $nuevo_cliente->fill(request()->input());
                $nuevo_cliente->save();
                return response()->json(  ['ok'=>  "Proveedor Actualizado"] );
            }catch( Exception  $ex){
                return response()->json(  ['err'=>   $ex->getMessage()  ] );      
            }
        
        }
    }



    public function delete(  $id= NULL){
        $reg= Proveedores::find(  $id) ;
        if(  !is_null(  $reg) )  { 
            $reg->delete();
            return response()->json(  ['ok'=>  "Proveedor eliminado"] );
        }else{
            return response()->json(  ['err'=>  "ID inexistente" ] );   
        }
    }




    //VALIDACIONES


    private function  ruc_cedula_registrado($arg)
    {

        $cli =  Proveedores::where("CEDULA_RUC",  $arg)->first();
        return  !is_null($cli);
    }
}
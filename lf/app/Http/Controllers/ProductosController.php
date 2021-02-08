<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Productos;
use App\Models\Proveedores;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductosController extends Controller {

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index( )
    {
  
Artisan::call('storage:link'); 

        $buscado= "";
        $tipo= "";
        if(  request()->method() ==  "POST") {
             $buscado=  request()->input("buscado");
             $tipo= request()->input("tipo");
            }
 
        $productos=  Productos::orderBy("created_at");
        if(  $buscado !=  ""){
            $productos=  $productos
            ->whereRaw("  CODIGO LIKE '%$buscado%' or BARCODE LIKE '%$buscado%' or  DESCRIPCION LIKE '%$buscado%'  ")  ; 
        }
      
        //Filtrar por preventa o preparados
        if( $tipo !=  ""){
            $productos=  $productos->where("TIPO", "=",  $tipo);
        }

              //El formato de los datos
              $formato=  request()->header("formato");

              //Si es JSON retornar
              if ($formato == "json") {
                  $productos =  $productos->get();
                  return response()->json($productos);
              }
               
        $productos=  $productos->paginate( 10 );
        
         
        if( request()->ajax())
        return view('mod_admin.productos.grill',  ['productos'=>   $productos]);
        else
        return view('mod_admin.productos.index');
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET") {
            
            return view('mod_admin.productos.create');
        }
        else {
        
            //artisan
Artisan::call('storage:link');
/*** */

            
            try{
                $data = request()->input();

                DB::beginTransaction();
                $nuevo_producto =  new Productos();
                $nuevo_producto->fill($data);
                $nuevo_producto->save();

                //foto
                $path = "";
                $fileinst= request()->file('IMG');
                if(  !is_null( $fileinst) )
                $path = $fileinst->store(
                    'foodimg',
                    'public'
                ); 

                $nuevo_producto->IMG = "lf/public/images/$path";//lf/public/
                $nuevo_producto->save();
                DB::commit();
                return response()->json(['ok' =>  "Producto registrado"]);
            }catch( Exception  $ex){
                DB::rollBack();
                return response()->json(  ['err'=>   $ex->getMessage()  ] );      
            }
        
        }
    }


    
    public function update(  $id= NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  Productos::find($id);
            //ruta de img
            
            return view('mod_admin.productos.update',  ['producto' =>  $cli]);
        }
        else {

           
//artisan
Artisan::call('storage:link');
/*** */
            try{
                $id_= request()->input("REGNRO");
                DB::beginTransaction();
                $nuevo_producto =  Productos::find(   $id_);
                $nuevo_producto->fill(request()->input());
                  //foto
                  $path = "";
                  $fileinst= request()->file('IMG');
                  if(  !is_null( $fileinst) )
                  $path = $fileinst->store(
                      'foodimg',
                      'public'
                  );
                  if( $path !=  "")  $nuevo_producto->IMG= "lf/public/images/". $path; 
                $nuevo_producto->save(); 
                DB::commit(); 
                return response()->json(  ['ok'=>  "Producto Actualizado"] );
            }catch( Exception  $ex){
                DB::rollBack();
                return response()->json(  ['err'=>   $ex->getMessage()  ] );      
            }
        
        }
    }



    public function delete(  $id= NULL){
        $reg= Productos::find(  $id) ;
        if(  !is_null(  $reg) )  { 
            $reg->delete();
            return response()->json(  ['ok'=>  "Producto eliminado"] );
        }else{
            return response()->json(  ['err'=>  "ID inexistente" ] );   
        }
    }



    public function   get( $id= NULL){
        $reg= Productos::find(  $id) ;
        if(  !is_null(  $reg) )  { 
            
            return response()->json(  ['ok'=>  $reg  ] );
        }else{
            return response()->json(  ['err'=>  "ID inexistente" ] );   
        }
    }

 
}
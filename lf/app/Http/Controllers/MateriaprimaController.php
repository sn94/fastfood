<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Materiaprima ;
use App\Models\Proveedores;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MateriaprimaController extends Controller {

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
 
        $materiaprimas=  Materiaprima::orderBy("created_at");
        if(  $buscado !=  ""){
            $materiaprimas=  $materiaprimas
            ->whereRaw(" CODIGO LIKE '%$buscado%' or  DESCRIPCION LIKE '%$buscado%'  ")  ; 
        }
      
         

           //El formato de los datos
           $formato=  request()->header("formato");

           //Si es JSON retornar
           if ($formato == "json") {
               $materiaprimas =  $materiaprimas->get();
               return response()->json($materiaprimas);
           }

        $materiaprimas=  $materiaprimas->paginate( 10 );
        
         
        if( request()->ajax())
        return view('materiaprima.grill',  ['materiaprima'=>   $materiaprimas]);
        else
        return view('materiaprima.index');
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET") {
            
            return view('materiaprima.create');
        }
        else {
        

            
            try{
                $data = request()->input();

                DB::beginTransaction();
                $nuevo_producto =  new Materiaprima();
                $nuevo_producto->fill($data);
                $nuevo_producto->save();

                 
                $nuevo_producto->save();
                DB::commit();
                return response()->json(['ok' =>  "Materia prima registrada"]);
            }catch( Exception  $ex){
                DB::rollBack();
                return response()->json(  ['err'=>   $ex->getMessage()  ] );      
            }
        
        }
    }


    
    public function update(  $id= NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  Materiaprima::find($id); 
            return view('materiaprima.update',  ['materiaprima' =>  $cli]);
        }
        else {

           
//artisan
Artisan::call('storage:link');
/*** */
            try{
                $id_= request()->input("REGNRO");
                DB::beginTransaction();
                $nuevo_producto =  Materiaprima::find(   $id_);
                $nuevo_producto->fill(request()->input());
                 
                $nuevo_producto->save(); 
                DB::commit(); 
                return response()->json(  ['ok'=>  "Actualizado"] );
            }catch( Exception  $ex){
                DB::rollBack();
                return response()->json(  ['err'=>   $ex->getMessage()  ] );      
            }
        
        }
    }



    public function delete(  $id= NULL){
        $reg= Materiaprima::find(  $id) ;
        if(  !is_null(  $reg) )  { 
            $reg->delete();
            return response()->json(  ['ok'=>  "Eliminado"] );
        }else{
            return response()->json(  ['err'=>  "ID inexistente" ] );   
        }
    }



    public function   get( $id= NULL){
        $reg= Materiaprima::find(  $id) ;
        if(  !is_null(  $reg) )  { 
            
            return response()->json(  ['ok'=>  $reg  ] );
        }else{
            return response()->json(  ['err'=>  "ID inexistente" ] );   
        }
    }
 
}
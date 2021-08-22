<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Niveles ; 
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB; 
class NivelesController extends Controller {

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
 
        $niveles=  Niveles::orderBy("created_at");
        if(  $buscado !=  ""){
            $niveles=  $niveles
            ->whereRaw("  DESCRIPCION LIKE '%$buscado%'  ")  ; 
        }
      
        $niveles=  $niveles->paginate( 10 );
        
         
        if( request()->ajax())
        return view('niveles.grill',  ['niveles'=>   $niveles]);
        else
        return view('niveles.index');
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET") {
            
            return view('niveles.create');
        }
        else {
        

            
            try{
                $data = request()->input();

                DB::beginTransaction();
                $nuevo_producto =  new Niveles();
                $nuevo_producto->fill($data);
                $nuevo_producto->save();

                 
                $nuevo_producto->save();
                DB::commit();
                return response()->json(['ok' =>  "Nivel de usuario registrado"]);
            }catch( Exception  $ex){
                DB::rollBack();
                return response()->json(  ['err'=>   $ex->getMessage()  ] );      
            }
        
        }
    }


    
    public function update(  $id= NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  Niveles::find($id); 
            return view('niveles.update',  ['niveles' =>  $cli]);
        }
        else {
 
            try{
                $id_= request()->input("REGNRO");
                DB::beginTransaction();
                $nuevo_producto =  Niveles::find(   $id_);
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
        $reg= Niveles::find(  $id) ;
        if(  !is_null(  $reg) )  { 
            $reg->delete();
            return response()->json(  ['ok'=>  "Eliminado"] );
        }else{
            return response()->json(  ['err'=>  "ID inexistente" ] );   
        }
    }

 
}
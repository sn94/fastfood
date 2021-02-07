<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Familia ; 
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB; 
class FamiliaController extends Controller {

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
 
        $familias=  Familia::orderBy("created_at");
        if(  $buscado !=  ""){
            $familias=  $familias
            ->whereRaw("  DESCRIPCION LIKE '%$buscado%'  ")  ; 
        }

         //El formato de los datos
         $formato=  request()->header("formato");

         //Si es JSON retornar
         if ($formato == "json") {
             $familias =  $familias->get();
             return response()->json($familias);
         }

        $familias=  $familias->paginate( 10 );
        
         
        if( request()->ajax())
        return view('mod_admin.familia.grill',  ['familias'=>   $familias]);
        else
        return view('mod_admin.familia.index');
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET") {
            
            return view('mod_admin.familia.create');
        }
        else {
        

            
            try{
                $data = request()->input();

                DB::beginTransaction();
                $nuevo_producto =  new Familia();
                $nuevo_producto->fill($data);
                $nuevo_producto->save();

                 
                $nuevo_producto->save();
                DB::commit();
                return response()->json(['ok' =>  "Familia registrada"]);
            }catch( Exception  $ex){
                DB::rollBack();
                return response()->json(  ['err'=>   $ex->getMessage()  ] );      
            }
        
        }
    }


    
    public function update(  $id= NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  Familia::find($id); 
            return view('mod_admin.familia.update',  ['familia' =>  $cli]);
        }
        else {
 
            try{
                $id_= request()->input("REGNRO");
                DB::beginTransaction();
                $nuevo_producto =  Familia::find(   $id_);
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
        $reg= Familia::find(  $id) ;
        if(  !is_null(  $reg) )  { 
            $reg->delete();
            return response()->json(  ['ok'=>  "Eliminado"] );
        }else{
            return response()->json(  ['err'=>  "ID inexistente" ] );   
        }
    }

 
}
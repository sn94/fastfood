<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Medidas ; 
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MedidasController extends Controller {

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
 
        $medidas=  Medidas::orderBy("created_at");
        if(  $buscado !=  ""){
            $medidas=  $medidas
            ->whereRaw("  DESCRIPCION LIKE '%$buscado%'  ")  ; 
        }
      

           //El formato de los datos
           $formato =  request()->header("formato");

           //Si es JSON retornar
           if ($formato == "json") {
               $medidas =  $medidas->get();
               return response()->json($medidas);
           }
   
           if ($formato == "pdf") {
               $medidas =  $medidas->get();
               return $this->responsePdf("medidas.grill.simple",  $medidas, "medidas");
           }

           

        $medidas=  $medidas->paginate( 10 );
        if( request()->ajax())
        return view('medidas.grill.index',  ['medidas'=>   $medidas]);
        else
        return view('medidas.index');
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET") {
            
            return view('medidas.create');
        }
        else {
        

            
            try{
                $data = request()->input();

                DB::beginTransaction();
                $nuevo_producto =  new Medidas();
                $nuevo_producto->fill($data);
                $nuevo_producto->save();
 
                DB::commit();
                return response()->json(['ok' =>  "Registrado"]);
            }catch( Exception  $ex){
                DB::rollBack();
                return response()->json(  ['err'=>   $ex->getMessage()  ] );      
            }
        
        }
    }


    
    public function update(  $id= NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  Medidas::find($id); 
            return view('medidas.update',  ['medidas' =>  $cli]);
        }
        else {

           
//artisan
Artisan::call('storage:link');
/*** */
            try{
                $id_= request()->input("REGNRO");
                DB::beginTransaction();
                $nuevo_producto =  Medidas::find(   $id_);
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
        $reg= Medidas::find(  $id) ;
        if(  !is_null(  $reg) )  { 
            $reg->delete();
            return response()->json(  ['ok'=>  "Eliminado"] );
        }else{
            return response()->json(  ['err'=>  "ID inexistente" ] );   
        }
    }

 
}
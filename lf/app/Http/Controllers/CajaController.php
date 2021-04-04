<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Caja ; 
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller {

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
 
        $caja=  Caja::orderBy("created_at");
        if(  $buscado !=  ""){
            $caja=  $caja
            ->whereRaw("  DESCRIPCION LIKE '%$buscado%'  ")  ; 
        }
      

           //El formato de los datos
           $formato =  request()->header("formato");

           //Si es JSON retornar
           if ($formato == "json") {
               $caja =  $caja->get();
               return response()->json($caja);
           }
   
           if ($formato == "pdf") {
               $caja =  $caja->get();
               return $this->responsePdf("caja.grill.simple",  $caja, "caja");
           }
   
        $caja=  $caja->paginate( 10 );

        if( request()->ajax())
        return view('caja.grill.index',  ['caja'=>   $caja]);
        else
        return view('caja.index');
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET") {
            
            return view('caja.create');
        }
        else {
        

            
            try{
                $data = request()->input();

                DB::beginTransaction();
                $nuevo_producto =  new caja();
                $nuevo_producto->fill($data);
                $nuevo_producto->save();
 
                DB::commit();
                return response()->json(['ok' =>  "caja registrada"]);
            }catch( Exception  $ex){
                DB::rollBack();
                return response()->json(  ['err'=>   $ex->getMessage()  ] );      
            }
        
        }
    }


    
    public function update(  $id= NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  Caja::find($id); 
            return view('caja.update',  ['caja' =>  $cli]);
        }
        else {

           
//artisan
Artisan::call('storage:link');
/*** */
            try{
                $id_= request()->input("REGNRO");
                DB::beginTransaction();
                $nuevo_producto =  Caja::find(   $id_);
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
        $reg= Caja::find(  $id) ;
        if(  !is_null(  $reg) )  { 
            $reg->delete();
            return response()->json(  ['ok'=>  "Eliminado"] );
        }else{
            return response()->json(  ['err'=>  "ID inexistente" ] );   
        }
    }

 
}
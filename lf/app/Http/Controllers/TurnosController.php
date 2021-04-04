<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Turno ; 
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB; 
 

class TurnosController extends Controller {

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
 
        $turnos=  Turno::orderBy("created_at");
        if(  $buscado !=  ""){
            $turnos=  $turnos
            ->whereRaw("  DESCRIPCION LIKE '%$buscado%'  ")  ; 
        }
         //El formato de los datos
         $formato =  request()->header("formato");

         //Si es JSON retornar
         if ($formato == "json") {
             $turnos =  $turnos->get();
             return response()->json($turnos);
         }
      

          
         if ($formato == "pdf") {
            $turnos =  $turnos->get();
            return $this->responsePdf("turno.grill.simple",  $turnos, "Turnos");
        }


        $turnos=  $turnos->paginate( 10 );
        if( request()->ajax())
        return view('turno.grill.index',  ['turnos'=>   $turnos]);
        else
        return view('turno.index');
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET") {
            
            return view('turno.create');
        }
        else {
        

            
            try{
                $data = request()->input();

                DB::beginTransaction();
                $nuevo_producto =  new Turno();
                $nuevo_producto->fill($data);
                $nuevo_producto->save();

                 
                $nuevo_producto->save();
                DB::commit();
                return response()->json(['ok' =>  "Turno registrado"]);
            }catch( Exception  $ex){
                DB::rollBack();
                return response()->json(  ['err'=>   $ex->getMessage()  ] );      
            }
        
        }
    }


    
    public function update(  $id= NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  Turno::find($id); 
            return view('turno.update',  ['turno' =>  $cli]);
        }
        else {

           
//artisan
Artisan::call('storage:link');
/*** */
            try{
                $id_= request()->input("REGNRO");
                DB::beginTransaction();
                $nuevo_producto =  Turno::find(   $id_);
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
        $reg= Turno::find(  $id) ;
        if(  !is_null(  $reg) )  { 
            $reg->delete();
            return response()->json(  ['ok'=>  "Eliminado"] );
        }else{
            return response()->json(  ['err'=>  "ID inexistente" ] );   
        }
    }

 

 
}
<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cargo ; 
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB; 
class CargosController extends Controller {

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
 
        $cargos=  Cargo::orderBy("created_at");
        if(  $buscado !=  ""){
            $cargos=  $cargos
            ->whereRaw("  DESCRIPCION LIKE '%$buscado%'  ")  ; 
        }
      
        $cargos=  $cargos->paginate( 10 );
        
         
        if( request()->ajax())
        return view('mod_admin.cargo.grill',  ['cargos'=>   $cargos]);
        else
        return view('mod_admin.cargo.index');
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET") {
            
            return view('mod_admin.cargo.create');
        }
        else {
        

            
            try{
                $data = request()->input();

                DB::beginTransaction();
                $nuevo_producto =  new Cargo();
                $nuevo_producto->fill($data);
                $nuevo_producto->save();

                 
                $nuevo_producto->save();
                DB::commit();
                return response()->json(['ok' =>  "Cargo registrado"]);
            }catch( Exception  $ex){
                DB::rollBack();
                return response()->json(  ['err'=>   $ex->getMessage()  ] );      
            }
        
        }
    }


    
    public function update(  $id= NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  Cargo::find($id); 
            return view('mod_admin.cargo.update',  ['cargo' =>  $cli]);
        }
        else {

           
//artisan
Artisan::call('storage:link');
/*** */
            try{
                $id_= request()->input("REGNRO");
                DB::beginTransaction();
                $nuevo_producto =  Cargo::find(   $id_);
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
        $reg= Cargo::find(  $id) ;
        if(  !is_null(  $reg) )  { 
            $reg->delete();
            return response()->json(  ['ok'=>  "Eliminado"] );
        }else{
            return response()->json(  ['err'=>  "ID inexistente" ] );   
        }
    }

 
}
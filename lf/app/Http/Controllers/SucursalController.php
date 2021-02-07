<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sucursal ; 
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB; 
class SucursalController extends Controller {

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
 
        $sucursales=  Sucursal::orderBy("created_at");
        if(  $buscado !=  ""){
            $sucursales=  $sucursales
            ->whereRaw("  DESCRIPCION LIKE '%$buscado%'  ")  ; 
        }
      
        $sucursales=  $sucursales->paginate( 10 );
        
         
        if( request()->ajax())
        return view('mod_admin.sucursal.grill',  ['sucursales'=>   $sucursales]);
        else
        return view('mod_admin.sucursal.index');
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET") {
            
            return view('mod_admin.sucursal.create');
        }
        else {
        

            
            try{
                $data = request()->input();

                DB::beginTransaction();
                $nuevo_producto =  new Sucursal();
                $nuevo_producto->fill($data);
                $nuevo_producto->save();

                 
                $nuevo_producto->save();
                DB::commit();
                return response()->json(['ok' =>  "Sucursal registrada"]);
            }catch( Exception  $ex){
                DB::rollBack();
                return response()->json(  ['err'=>   $ex->getMessage()  ] );      
            }
        
        }
    }


    
    public function update(  $id= NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  Sucursal::find($id); 
            return view('mod_admin.sucursal.update',  ['sucursal' =>  $cli]);
        }
        else {

           
//artisan
Artisan::call('storage:link');
/*** */
            try{
                $id_= request()->input("REGNRO");
                DB::beginTransaction();
                $nuevo_producto =  Sucursal::find(   $id_);
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
        $reg= Sucursal::find(  $id) ;
        if(  !is_null(  $reg) )  { 
            $reg->delete();
            return response()->json(  ['ok'=>  "Eliminado"] );
        }else{
            return response()->json(  ['err'=>  "ID inexistente" ] );   
        }
    }

 
}
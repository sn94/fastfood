<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Parametros ; 
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ParametrosController extends Controller {
 


    public function create()
    {
        if (request()->getMethod()  ==  "GET") {
            
            $params =   Parametros::where("SUCURSAL", session("SUCURSAL"))->first();
            if(  is_null($params) )
            return view('parametros.index');
            else
            return view('parametros.index', ["parametros"=>  $params]);
        }
        else {
        

            
            try{
                $data = request()->input();

                DB::beginTransaction();
                $nuevo_producto = Parametros::where("SUCURSAL", session("SUCURSAL"))->first();
                if(  is_null( $nuevo_producto) )
                $nuevo_producto= new Parametros();

                $nuevo_producto->fill($data);
                $nuevo_producto->save();
 
                DB::commit();
                return response()->json(['ok' =>  "parametros registrados"]);
            }catch( Exception  $ex){
                DB::rollBack();
                return response()->json(  ['err'=>   $ex->getMessage()  ] );      
            }
        
        }
    }


  

 
}
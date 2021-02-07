<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cargo ;
use App\Models\Compras; 
use App\Models\Compras_detalles;
use App\Models\Materiaprima; 
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB; 
class DepositoController extends Controller {

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index( )
    {
 
     
         
        
        //if( request()->ajax())
        //return view('mod_admin.deposito.grill', ['contexto' =>  $contexto,  'registros' => $registro->get()]);
         return view('mod_admin.deposito.index');
    }




    public function movimientos_materia_prima(){

        $buscado= "";
        $contexto=  request()->input("contexto");
        if(  request()->method() ==  "POST")  $buscado=  request()->input("buscado");
        //Buscar producto terminado o materia prima
        $registro=  Materiaprima::orderBy("created_at")  ;
        
        if(  $buscado !=  ""){
            $registro=  $registro
            ->whereRaw("  DESCRIPCION LIKE '%$buscado%'  ")  ; 
        }
      
    }

 

    public function recepcion(  $CONTEXTO )
    {
        if (request()->getMethod()  ==  "GET") {
            
            $resource_url= url("productos/buscar");
            $resource_url_item= url("productos/get");

            if(  $CONTEXTO == "M"){
                $resource_url= url("materia-prima/buscar");
                $resource_url_item= url("materia-prima/get");
            }
            //URL PARA REGISTRAR LA COMPRA
            $POST_COMPRA_URL= url("deposito-entrada/$CONTEXTO");
            //titulo de pantalla
            $TITULO=  $CONTEXTO=="M" ? "Materia prima" : "Productos";
            return view(
                'mod_admin.deposito.entrada.recepcion',
                [  'TITULO'=> $TITULO, 'FORM_URL' => $POST_COMPRA_URL, 'RESOURCE_URL' => $resource_url,  'RESOURCE_URL_ITEM' =>  $resource_url_item]
            );
        }
        else {
        
            $Datos= request()->input() ;
            $CABECERA= $Datos['CABECERA'];
            $DETALLE= $Datos['DETALLE'];
           
            
            try{ 
                DB::beginTransaction();
                //CABECERA
                $n_compra= new Compras();
                $n_compra->fill( $CABECERA );
                $n_compra->save();
                //DETALLE
               
                foreach ($DETALLE as $row) :
                    $datarow= $row;
                    $datarow['COMPRA_ID']= $n_compra->REGNRO;
                    
                    $d_compra =  new Compras_detalles();
                    $d_compra->fill( $datarow);
                    $d_compra->save();
                endforeach;
                

                DB::commit();
                return response()->json(['ok' =>  "Entrada registrada"]);
            }catch( Exception  $ex){
                DB::rollBack();
                return response()->json(  ['err'=>   $ex->getMessage()  ] );      
            }
        
        }
    }

    

    public function compra(  $CONTEXTO )
    {
        if (request()->getMethod()  ==  "GET") {
            
            $resource_url= url("productos/buscar");
            $resource_url_item= url("productos/get");

            if(  $CONTEXTO == "M"){
                $resource_url= url("materia-prima/buscar");
                $resource_url_item= url("materia-prima/get");
            }
            //URL PARA REGISTRAR LA COMPRA
            $POST_COMPRA_URL= url("deposito-compra/$CONTEXTO");
            //titulo de pantalla
            $TITULO=  $CONTEXTO=="M" ? "Materia prima" : "Productos";
            return view(
                'mod_admin.deposito.entrada.compra',
                [  'TITULO'=> $TITULO, 'FORM_URL' => $POST_COMPRA_URL, 'RESOURCE_URL' => $resource_url,  'RESOURCE_URL_ITEM' =>  $resource_url_item]
            );
        }
        else {
        
            $Datos= request()->input() ;
            $CABECERA= $Datos['CABECERA'];
            $DETALLE= $Datos['DETALLE'];
           
            
            try{ 
                DB::beginTransaction();
                //CABECERA
                $n_compra= new Compras();
                $n_compra->fill( $CABECERA );
                $n_compra->save();
                //DETALLE
               
                foreach ($DETALLE as $row) :
                    $datarow= $row;
                    $datarow['COMPRA_ID']= $n_compra->REGNRO;
                    
                    $d_compra =  new Compras_detalles();
                    $d_compra->fill( $datarow);
                    $d_compra->save();
                endforeach;
                

                DB::commit();
                return response()->json(['ok' =>  "Entrada registrada"]);
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
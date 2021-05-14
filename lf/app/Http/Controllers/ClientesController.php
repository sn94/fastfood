<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libs\pdf_gen\PDF;
use App\Models\Clientes;
use Exception;
use Facade\FlareClient\Http\Client;

class ClientesController extends Controller
{

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index()
    {


        try {
            //Ya existen clientes?
            $nro =  Clientes::count();
            if ($nro == 0) {
                $clienteDefault = new  Clientes();
                $clienteDefault->fill(['REGNRO' => '1', 'CEDULA_RUC' =>  '44444401-7', 'NOMBRE' => 'CLIENTE OCASIONAL']);
                $clienteDefault->save();
            }

            $buscado = ""; //busqueda por patrones
            $CIUDAD = "";

            if (request()->method() ==  "POST") {
                $buscado =  request()->has("buscado") ?  request()->input("buscado") :  "";
                $CIUDAD =  request()->has("CIUDAD") ?  request()->input("CIUDAD") :  "";
            }

            $clientes = Clientes::orderBy("created_at");
            if ($buscado !=  "") {
                $clientes =  $clientes
                    ->whereRaw(" CEDULA_RUC LIKE '%$buscado%' or  NOMBRE LIKE '%$buscado%'  ");
            }
            if ($CIUDAD !=  "") {
                $clientes =  $clientes
                    ->where("CIUDAD", $CIUDAD);
            }


           

            //El formato de los datos
            $formato =  request()->header("formato");

            $formato =  is_null($formato) ?  "html"  :   $formato;

            if ($formato ==   "json")
                return  response()->json($clientes->get());

            if ($formato ==   "pdf")
                return $this->responsePdf(    "clientes.grill.simple",  $clientes->get(), "Clientes");

            if ($formato ==  "html") { ///Html
                $resultados =  $clientes->paginate(20);
                if (request()->ajax())
                    return view('clientes.grill.grill',  ['clientes' =>   $resultados]);
                else
                    return view('clientes.index');
            }
        } catch (Exception $e) {
            response()->json(['err' => $e->getMessage()]);
        }
    }




    public function create()
    {

        if (request()->getMethod()  ==  "GET")
            if (request()->ajax())
                return view('clientes.create_ajax');
            else
                return view('clientes.create');
        else {

            //
            //Ya existen clientes?
            $nro =  Clientes::count();
            if ($nro == 0) {
                $clienteDefault = new  Clientes();
                $clienteDefault->fill(['REGNRO' => '1', 'RUC' =>  '44444401-7', 'NOMBRE' => 'CLIENTE OCASIONAL']);
                $clienteDefault->save();
            }


            if ($this->ruc_cedula_registrado(request()->input("CEDULA_RUC")))
                return response()->json(['err' =>  "CÉDULA/RUC ya existe"]);

            try {
                $nuevo_cliente =  new Clientes();
                $nuevo_cliente->fill(request()->input());
                $nuevo_cliente->save();
                return response()->json(['ok' =>  $nuevo_cliente->REGNRO]);
            } catch (Exception  $ex) {
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function update($id = NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  Clientes::find($id);
            return view('clientes.create',  ['cliente' =>  $cli]);
        } else {


            try {
                $id_ = request()->input("REGNRO");
                $nuevo_cliente =  Clientes::find($id_);


                if ($nuevo_cliente->CEDULA_RUC !=  request()->input("CEDULA_RUC")  &&  $this->ruc_cedula_registrado(request()->input("CEDULA_RUC")))
                    return response()->json(['err' =>  "CÉDULA/RUC ya existe"]);


                $nuevo_cliente->fill(request()->input());
                $nuevo_cliente->save();
                return response()->json(['ok' => $nuevo_cliente->REGNRO]);
            } catch (Exception  $ex) {
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function delete($id = NULL)
    {
        $reg = Clientes::find($id);
        if (!is_null($reg)) {
            $reg->delete();
            return response()->json(['ok' =>  "Cliente eliminado"]);
        } else {
            return response()->json(['err' =>  "ID inexistente"]);
        }
    }




    //VALIDACIONES


    private function  ruc_cedula_registrado($arg)
    {

        $cli =  Clientes::where("CEDULA_RUC",  $arg)->first();
        return  !is_null($cli);
    }






 
}

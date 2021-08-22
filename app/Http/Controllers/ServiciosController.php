<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Servicios;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ServiciosController extends Controller
{

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index()
    {



        $buscado = "";
        if (request()->method() ==  "POST")  $buscado =  request()->input("buscado");

        $servicios =  Servicios::orderBy("ORDEN", "ASC");
        if ($buscado !=  "") {
            $servicios =  $servicios
                ->whereRaw("  DESCRIPCION LIKE '%$buscado%'  ");
        }


        //El formato de los datos
        $formato =  request()->header("formato");

        //Si es JSON retornar
        if ($formato == "json") {
            $servicios =  $servicios->get();
            return response()->json($servicios);
        }

        if ($formato == "pdf") {
            $servicios =  $servicios->get();
            return $this->responsePdf("servicios.grill.simple",  $servicios, "Servicios");
        }



        $servicios =  $servicios
        ->select("servicios.*", DB::raw("FORMAT(servicios.COSTO, 0,'de_DE') AS COSTO") )
        ->paginate(20);

        if (request()->ajax())
            return view('servicios.grill.index',  ['servicios' =>   $servicios]);
        else
            return view('servicios.index');
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET") {

            return view('servicios.create');
        } else {



            try {
                $data = request()->input();

                DB::beginTransaction();
                $nuevo_producto =  new Servicios();
                $nuevo_producto->fill($data);
                $nuevo_producto->save();

                DB::commit();
                return response()->json(['ok' =>  "Servicio registrado"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function update($id = NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  Servicios::select("servicios.*", DB::raw("FORMAT(servicios.COSTO, 0,'de_DE') AS COSTO") )
            ->find($id);
            return view('servicios.create',  ['servicio'=>  $cli]);
        } else {

 
            try {
                $id_ = request()->input("REGNRO");
                DB::beginTransaction();
                $nuevo_producto =  Servicios::find($id_);
                $nuevo_producto->fill(request()->input());

                $nuevo_producto->save();
                DB::commit();
                return response()->json(['ok' =>  "Actualizado"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function delete($id = NULL)
    {
        $reg = Servicios::find($id);
        if (!is_null($reg)) {
            $reg->delete();
            return response()->json(['ok' =>  "Eliminado"]);
        } else {
            return response()->json(['err' =>  "ID inexistente"]);
        }
    }
}

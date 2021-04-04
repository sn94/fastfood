<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ciudades;
use App\Models\Ciudades_departa;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class CiudadController extends Controller
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

        $ciudades =  Ciudades::orderBy("departa");
        if ($buscado !=  "") {
            $ciudades =  $ciudades
                ->whereRaw("  ciudad LIKE '%$buscado%'  ");
        }


        //El formato de los datos
        $formato =  request()->header("formato");

        //Si es JSON retornar
        if ($formato == "json") {
            $ciudades =  $ciudades->get();
            return response()->json($ciudades);
        }

        if ($formato == "pdf") {
            $ciudades =  $ciudades->get();
            return $this->responsePdf("ciudades.grill.simple",  $ciudades, "Ciudades");
        }



        $ciudades =  $ciudades->get();
        if (request()->ajax())
            return view('ciudades.grill.index',  ['ciudades' =>   $ciudades]);
        else
            return view('ciudades.index');
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET") {

            return view('ciudades.create');
        } else {



            try {
                $data = request()->input();

                DB::beginTransaction();
                $nuevo_producto =  new Ciudades();
                $nuevo_producto->fill($data);
                $nuevo_producto->save();

                DB::commit();
                return response()->json(['ok' =>  "registrado"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function update($id = NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  Ciudades::find($id);
            return view('ciudades.update',  ['ciudades' =>  $cli]);
        } else {


            //artisan
            Artisan::call('storage:link');
            /*** */
            try {
                $id_ = request()->input("REGNRO");
                DB::beginTransaction();
                $nuevo_producto =  Ciudades::find($id_);
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
        $reg = Ciudades::find($id);
        if (!is_null($reg)) {
            $reg->delete();
            return response()->json(['ok' =>  "Eliminado"]);
        } else {
            return response()->json(['err' =>  "ID inexistente"]);
        }
    }
}

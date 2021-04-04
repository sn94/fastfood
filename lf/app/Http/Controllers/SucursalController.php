<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sucursal;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SucursalController extends Controller
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

        $sucursales =  Sucursal::orderBy("created_at");
        if ($buscado !=  "") {
            $sucursales =  $sucursales
                ->whereRaw("  DESCRIPCION LIKE '%$buscado%'  ");
        }

        //El formato de los datos
        $formato =  request()->header("formato");

        //Si es JSON retornar
        if ($formato == "json") {
            $sucursales =  $sucursales->get();
            return response()->json($sucursales);
        }


        if ($formato == "pdf") {
            $sucursales =  $sucursales->get();
            return $this->responsePdf("sucursal.grill.simple",  $sucursales, "Sucursales");
        }

        $sucursales =  $sucursales->paginate(10);
        if (request()->ajax())
            return view('sucursal.grill.index',  ['sucursales' =>   $sucursales]);
        else
            return view('sucursal.index');
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET") {

            return view('sucursal.create');
        } else {

            $data = request()->input();

            if ($data['MATRIZ'] == "S") {
                $existe = Sucursal::where("MATRIZ", "S")->first();
                if (!is_null($existe))
                    return response()->json(['err' => "LA SUCURSAL $existe->DESCRIPCION YA ESTA DEFINIDA COMO MATRIZ"]);
            }

            try {


                DB::beginTransaction();
                $nuevo_producto =  new Sucursal();
                $nuevo_producto->fill($data);
                $nuevo_producto->save();


                $nuevo_producto->save();
                DB::commit();
                return response()->json(['ok' =>  "Sucursal registrada"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function update($id = NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  Sucursal::find($id);
            return view('sucursal.update',  ['sucursal' =>  $cli]);
        } else {


            //artisan
            Artisan::call('storage:link');
            /*** */
            $data    = request()->input();
            if ($data['MATRIZ'] == "S") {
                $existe = Sucursal::where("MATRIZ", "S")->first();
                if (!is_null($existe)  &&  $existe->REGNRO  !=  $data['REGNRO'])

                    return response()->json(['err' => "LA SUCURSAL $existe->DESCRIPCION YA ESTA DEFINIDA COMO MATRIZ"]);
            }


            try {
                $id_ = request()->input("REGNRO");
                DB::beginTransaction();
                $nuevo_producto =  Sucursal::find($id_);
                $nuevo_producto->fill($data);

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
        $reg = Sucursal::find($id);
        if (!is_null($reg)) {
            $reg->delete();
            return response()->json(['ok' =>  "Eliminado"]);
        } else {
            return response()->json(['err' =>  "ID inexistente"]);
        }
    }
}

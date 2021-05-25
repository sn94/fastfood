<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\OrigenVenta;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class OrigenVentaController extends Controller
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

        $origenes_venta =  OrigenVenta::orderBy("ORDEN", "ASC");
        if ($buscado !=  "") {
            $origenes_venta =  $origenes_venta
                ->whereRaw("  DESCRIPCION LIKE '%$buscado%'  ");
        }


        //El formato de los datos
        $formato =  request()->header("formato");

        //Si es JSON retornar
        if ($formato == "json") {
            $origenes_venta =  $origenes_venta->get();
            return response()->json($origenes_venta);
        }

        if ($formato == "pdf") {
            $origenes_venta =  $origenes_venta->get();
            return $this->responsePdf("origen_venta.grill.simple",  $origenes_venta, "Orígenes de venta");
        }



        $origenes_venta =  $origenes_venta->paginate(20);

        if (request()->ajax())
            return view('origen_venta.grill.index',  ['origenes_venta' =>   $origenes_venta]);
        else
            return view('origen_venta.index');
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET") {

            return view('origen_venta.create');
        } else {



            try {
                $data = request()->input();

                DB::beginTransaction();
                $nuevo_producto =  new OrigenVenta();
                $nuevo_producto->fill($data);
                $nuevo_producto->save();

                DB::commit();
                return response()->json(['ok' =>  "Origen de venta registrado"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function update($id = NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  OrigenVenta::find($id);
            return view('origen_venta.create',  ['origen_venta' =>  $cli]);
        } else {


            //artisan
            Artisan::call('storage:link');
            /*** */
            try {
                $id_ = request()->input("REGNRO");
                DB::beginTransaction();
                $nuevo_producto =  OrigenVenta::find($id_);
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
        $reg = OrigenVenta::find($id);
        if (!is_null($reg)) {
            $reg->delete();
            return response()->json(['ok' =>  "Eliminado"]);
        } else {
            return response()->json(['err' =>  "ID inexistente"]);
        }
    }
}

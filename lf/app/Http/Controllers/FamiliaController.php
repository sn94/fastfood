<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Familia;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class FamiliaController extends Controller
{

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index()
    {

       // echo  is_null(Familia::max("NRO_PESTANA")) ? 1 :  Familia::max("NRO_PESTANA")+1 ;

        $buscado = "";
        if (request()->method() ==  "POST")  $buscado =  request()->input("buscado");

        $familias =  Familia::orderBy("NRO_PESTANA");
        if ($buscado !=  "") {
            $familias =  $familias
                ->whereRaw("  DESCRIPCION LIKE '%$buscado%'  ");
        }

        //El formato de los datos
        $formato =  request()->header("formato");

        //Si es JSON retornar
        if ($formato == "json") {
            $familias =  $familias->get();
            return response()->json($familias);
        }
 
          if ($formato == "pdf") {
              $familias =  $familias->get();
              return $this->responsePdf("familia.grill.simple",  $familias, "Familias de productos");
          }
        $familias =  $familias->paginate(10);
        if (request()->ajax())
            return view('familia.grill.index',  ['familias' =>   $familias]);
        else
            return view('familia.index', ['familias' =>   $familias]);
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET") {

            return view('familia.create');
        } else {



            try {
                $data = request()->input();

                DB::beginTransaction();
                $nuevo_producto =  new Familia();
                $data['NRO_PESTANA'] =   is_null(Familia::max("NRO_PESTANA")) ? 1 :  Familia::max("NRO_PESTANA")+1;
                $nuevo_producto->fill($data);
                $nuevo_producto->save();

 
                DB::commit();
                return response()->json(['ok' =>  "Familia registrada"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function update($id = NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  Familia::find($id);
            return view('familia.update',  ['familia' =>  $cli]);
        } else {

            try {
                $id_ = request()->input("REGNRO");
                DB::beginTransaction();
                $nuevo_producto =  Familia::find($id_);
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
        $reg = Familia::find($id);
        if (!is_null($reg)) {
            $reg->delete();
            return response()->json(['ok' =>  "Eliminado"]);
        } else {
            return response()->json(['err' =>  "ID inexistente"]);
        }
    }



    public function posiciones()
    {


        if (request()->getMethod()  == "GET") {
            $fa = Familia::orderBy("NRO_PESTANA")->get();
            return view("familia.posicionador", ['familias' => $fa]);
        }

        $data = request()->input();
        try {
            DB::beginTransaction();
            foreach ($data as $fami_pos) :
                $FAMI =  Familia::where("REGNRO",  $fami_pos['REGNRO'])
                    ->first();
                $FAMI->NRO_PESTANA =  $fami_pos['NRO_PESTANA'];
                $FAMI->save();
            endforeach;
            DB::commit();
            return response()->json(['ok' =>  "actualizado"]);
        } catch (Exception $ex) {
            return response()->json(['err' =>  $ex->getMessage()]);
        }
    }
}

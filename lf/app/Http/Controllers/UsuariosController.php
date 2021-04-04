<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sucursal;
use App\Models\Usuario;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

use PDFGEN;

class UsuariosController extends Controller
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

        $usuarios =  Usuario::orderBy("created_at");
        if ($buscado !=  "") {
            $usuarios =  $usuarios
                ->whereRaw("  USUARIO LIKE '%$buscado%'  ")
                ->orWhereRaw("  NOMBRES LIKE '%$buscado%'  ");
        }

        //El formato de los datos
        $formato =  request()->header("formato");

        $formato =  is_null($formato) ?  "html"  :   $formato;

        if ($formato ==   "json")
            return  response()->json($usuarios->get());

        if ($formato ==   "pdf")
            return $this->pdf($usuarios->get());

        if ($formato ==  "html") {
            $usuarios =  $usuarios->paginate(10);
            if (request()->ajax())
                return view('usuario.grill.index',  ['usuarios' =>   $usuarios]);
            else
                return view('usuario.index');
        }
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET") {

            return view('usuario.create');
        } else {

            $data = request()->input();
            //EXISTE USUARIO
            $usua = Usuario::where("USUARIO",  $data['USUARIO'])->first();
            if (!is_null($usua))
                return response()->json(['err' =>  'Usuario no ese nombre ya existe']);


            try {

                if (isset($data['PASS']))
                    $data['PASS'] =  password_hash($data['PASS'], PASSWORD_BCRYPT);
                DB::beginTransaction();
                $nuevo_producto =  new Usuario();
                $nuevo_producto->fill($data);
                $nuevo_producto->save();


                $nuevo_producto->save();
                DB::commit();
                return response()->json(['ok' =>  "Usuario registrado"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function update($id = NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  Usuario::find($id);
            return view('usuario.update',  ['usuario' =>  $cli]);
        } else {


            //artisan
            Artisan::call('storage:link');
            /*** */
            try {
                $id_ = request()->input("REGNRO");
                DB::beginTransaction();
                $nuevo_producto =  Usuario::find($id_);
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
        $reg = Usuario::find($id);
        if (!is_null($reg)) {
            $reg->delete();
            return response()->json(['ok' =>  "Eliminado"]);
        } else {
            return response()->json(['err' =>  "ID inexistente"]);
        }
    }





    public function sign_in()
    {


        if (request()->getMethod() == "POST") {
            $data = request()->input();

            //EXISTE USUARIO
            $usua = Usuario::where("USUARIO",  $data['USUARIO'])->first();
            if (is_null($usua))
                return response()->json(['err' =>  'Usuario inexistente']);
            //pass ok
            if (!password_verify($data['PASS'],   $usua->PASS))
                return response()->json(['err' =>  'Contraseña invalida']);


            $casaMatriz = Sucursal::find($usua->SUCURSAL)->MATRIZ;
            //crear sesion
            session([
                'ID' =>  $usua->REGNRO,  'USUARIO' => $data['USUARIO'],   'SUCURSAL' => $usua->SUCURSAL,
                'NIVEL' =>  $usua->NIVEL,  'MATRIZ' =>  $casaMatriz
            ]);

            (new SesionesController())->restaurarSesion(); //Si hubiere



            $PaginaDeInicio = "";
 
            //El cajero tiene sesion abierta?
            if (! is_null( (new SesionesController())->tieneSesionAbierta()))     $PaginaDeInicio = url("sesiones/create");
            else{
                if ($usua->NIVEL ==  "SUPER")   $PaginaDeInicio =  url("modulo-administrativo");
                if ($usua->NIVEL ==  "CAJA")   $PaginaDeInicio =  url("modulo-caja");
            }
          

            return response()->json(['ok' =>  $PaginaDeInicio]);
        }


        return view("usuario.login");
    }



    public function sign_out()
    {
        session()->flush();
        return redirect(url("usuario/sign-in"));
    }



    public function pdf($lista)
    {

        $pdf = PDFGEN::loadView("usuario.grill.simple", ["usuarios" =>  $lista]);
        return $pdf->download("Usuarios.pdf");
    }
}

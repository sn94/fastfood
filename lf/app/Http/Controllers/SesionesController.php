<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sesiones;
use App\Models\Ventas;
use App\Models\Ventas_det;
use Exception;
use Illuminate\Support\Facades\DB;

class SesionesController extends Controller
{

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index()
    {
         
        //Modulo de origen
        $MODULO_FLAG =    isset($_GET['m']) ? $_GET['m'] :  "";

       

        $SUCURSAL =   request()->has("SUCURSAL") ?  request()->input("SUCURSAL") :   session("SUCURSAL");
        $USERID =  request()->has("USUARIO") ?  request()->input("USUARIO") : ($MODULO_FLAG == "c" ? session("ID") : null);
        $ESTADO =   request()->has("ESTADO") ?  request()->input("ESTADO") :  "A"; //Abiertas, mostradas por defecto

        $sesiones = Sesiones::where("SUCURSAL",  $SUCURSAL)
            ->where("ESTADO", $ESTADO);

        //Filtrar por Id de usuario
        if (!is_null($USERID))
            $sesiones =  $sesiones->where("CAJERO", $USERID);

        $sesiones =  $sesiones->select(
            "sesiones.*",
            DB::raw("FORMAT(EFECTIVO_INI,0,'de_DE') AS EFECTIVO_INI"),
            DB::raw("FORMAT(TOTAL_EFE,0,'de_DE') AS TOTAL_EFE"),
            DB::raw("FORMAT(TOTAL_TAR,0,'de_DE') AS TOTAL_TAR"),
            DB::raw("FORMAT(TOTAL_GIRO,0,'de_DE') AS TOTAL_GIRO")
           
        );
         
        $sesiones =   $sesiones->orderBy("REGNRO", "DESC");
        $formato =  request()->header("formato");

        $formato =  is_null($formato) ?  "html"  :   $formato;

        //Si es JSON retornar
        if ($formato == "json") {
            $sesiones =  $sesiones->get();
            return response()->json($sesiones);
        }

        if ($formato ==   "pdf")
            return $this->responsePdf("sesiones.index.simple",  $sesiones->get(), "Sesiones");

        if ($formato ==  "html") {

            $params =    ['SESIONES' => $sesiones->paginate(20), 'SUCURSAL' => $SUCURSAL, 'USUARIO' => $USERID, 'ESTADO' => $ESTADO];

            if (request()->ajax())
                return view("sesiones.index.grill",  $params);
            else {
                //Plantilla
                $nombrePlantilla = session("NIVEL") == "SUPER" ? "templates.admin.index" : "templates.caja.index";
                $params['PLANTILLA'] = $nombrePlantilla;
                return view("sesiones.index.index", $params);
            }
        }
    }




    public function tieneSesionAbierta( $SESIONID= NULL)
    {
        $ses_id= is_null(  $SESIONID) ? session("ID"):  $SESIONID;
        $sesionAbierta =  Sesiones::where("CAJERO",   $ses_id)
            ->where("SUCURSAL", session("SUCURSAL"))
            ->where("ESTADO", "A")
            ->first();
        return  $sesionAbierta;
    }


    public function restaurarSesion()
    {
        //session
        $nueva_sesion = $this->tieneSesionAbierta();
        if (!is_null($nueva_sesion))
            request()->session()->put('SESION', $nueva_sesion->REGNRO);
    }

    public function create()
    {

        if (request()->getMethod()  ==  "GET") {
            //Verificar si este Cajero tiene ya sesiones abiertas
            $sesionAbierta = $this->tieneSesionAbierta();
            if (!is_null($sesionAbierta)) {
                $params = [];
                //Ya esta abierta  
                $params = ['SESION' => $sesionAbierta];
                return view('sesiones.apertura.index',  $params);
            }


            $params = [];
            //Nueva sesion
            $PROXIMO_ID =  (new Sesiones())->PROXIMO_ID();
            $params = ['ID_SESION' => $PROXIMO_ID];

            return view('sesiones.apertura.index',  $params);
        } else {
            try {
                $data = request()->input();
                DB::beginTransaction();
                $nueva_sesion =  new Sesiones();
                $nueva_sesion->fill($data);
                $nueva_sesion->save();
                DB::commit();

                //session
                request()->session()->put('SESION', $nueva_sesion->REGNRO);

                return response()->json(['ok' =>  "Sesión creada"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function  totalesArqueo($SESION,  $ARRAY_RETURN = FALSE)
    {

        //Definir formato de retorno 
        $formato =  request()->header("formato");
        $formato =  is_null($formato) ?  "html"  :   $formato;



        if ($formato ==   "json")
            return  response()->json(Sesiones::where("CAJERO", session("ID"))->get());

        //Obtener datos de la sesion 
        $sesion =  Sesiones::find($SESION);
        //Todos los ticket expedidos (no anulados)
        $tickets_expedidos = Ventas::where("SESION",  $sesion->REGNRO)
            ->where("CAJERO", $sesion->CAJERO)
            ->where("ESTADO", "A")
            ->get();
        //Tickets anulados
        $tickets_anulados = Ventas::where("SESION",  $sesion->REGNRO)
            ->where("CAJERO", $sesion->CAJERO)
            ->where("ESTADO", "B")->get();

        //Totales discriminados por forma de pago
        $totales =    Ventas::where("SESION",  $sesion->REGNRO)
            ->select(DB::raw("sum(TOTAL) AS TOTAL"), "FORMA")
            ->groupBy("FORMA")
            ->get();
        //TARJETA TIGO_MONEY EFECTIVO
        $t_efe = 0;
        $t_tar = 0;
        $t_giro = 0;
        foreach ($totales as  $recaudado) :
            //Efectivo
            if ($recaudado->FORMA == "EFECTIVO")
                $t_efe =  $recaudado->TOTAL;
            else {
                //Tarjeta
                if ($recaudado->FORMA == "TARJETA")
                    $t_tar = $recaudado->TOTAL;
                elseif ($recaudado->FORMA == "TIGO_MONEY")
                    $t_giro =   $recaudado->TOTAL;
            }
        endforeach;
        $totalTodo =   $t_efe + $t_tar  + $t_giro;
        $totalesVentas = ['TOTAL' => $totalTodo, 'EFECTIVO' => $t_efe,  'TARJETA' =>  $t_tar,  'TIGO_MONEY' =>   $t_giro];

        //Productos vendidos
        $detalleVentas =  Ventas_det::join("ventas",  "ventas_det.VENTA_ID", "=", "ventas.REGNRO")
            ->where("ventas.SESION",  $sesion->REGNRO)
            ->where("ventas.SUCURSAL", $sesion->SUCURSAL)
            ->select("ventas_det.ITEM", DB::raw("sum(ventas_det.P_UNITARIO * ventas_det.CANTIDAD) as IMPORTE"), DB::raw("count(ventas_det.ITEM) as CANTIDAD "))
            ->groupBy("ventas_det.ITEM")
            ->with("producto")
            ->get();
        $parametros =
            [
                "SESION" => $sesion, 'TICKETS' => $tickets_expedidos, 'ANULADOS' => $tickets_anulados,
                'TOTALES' => $totalesVentas, 'VENDIDOS' =>  $detalleVentas
            ];

        if ($ARRAY_RETURN)
            return $parametros;
        else {

            if ($formato ==   "pdf")
                return $this->responsePdf("sesiones.arqueo.simple", $parametros, "INFORME DE ARQUEO");
            if ($formato ==  "html")
                return view("sesiones.arqueo.form", $parametros);
        }
    }




    public function cerrar($SESIONID =  NULL)
    {
        /*  if( is_null($this->esMiSesion($SESIONID) )   )
        return response()->json(['err' =>  ""]);*/
/*
        
        if (is_null($this->tieneSesionAbierta( $SESIONID)))
            return redirect("sesiones/create");*/
        //Obtener datos de la sesion 
       
        $SesionId =  is_null($SESIONID) ? session("SESION")  :  $SESIONID;
        $sesion =  Sesiones::find( $SesionId);
        $parametros =  $this->totalesArqueo($SesionId, TRUE);

        if (request()->getMethod() == "GET") {
            return view("sesiones.arqueo.index", $parametros);
        } else {

            try {
                $sesion->fill(

                    [
                        "ESTADO" => "C",
                        "FECHA_CIE" => date("Y-m-d"),
                        "HORA_CIE" => date("H:i"),
                        'TOTAL_EFE' => $parametros['TOTALES']['EFECTIVO'],
                        'TOTAL_TAR' => $parametros['TOTALES']['TARJETA'],
                        "TOTAL_GIRO" => $parametros['TOTALES']['TIGO_MONEY']
                    ]
                );
                $sesion->save();
                //session
                request()->session()->forget('SESION');
                return response()->json(['ok' =>  "Sesión cerrada"]);
            } catch (Exception $e) {
                return response()->json(['err' =>  $e->getMessage()]);
            }
        }
    }
}

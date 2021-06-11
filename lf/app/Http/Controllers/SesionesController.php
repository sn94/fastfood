<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\GenericEmailSender;
use App\Models\Parametros;
use App\Models\Sesiones;
use App\Models\Ventas;
use App\Models\Ventas_det;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\Foreach_;

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
        $fecha_desde = request()->has("FECHA_DESDE") ? request()->input("FECHA_DESDE") : NULL;
        $fecha_hasta = request()->has("FECHA_HASTA") ? request()->input("FECHA_HASTA") : NULL;

        $sesiones = Sesiones::where("sesiones.SUCURSAL",  $SUCURSAL)->where("sesiones.ESTADO", $ESTADO);
        //join("ventas", "ventas.SESION", "=", "sesiones.REGNRO")
      //  ->leftJoin("servicios", "servicios.REGNRO", "=", "ventas.SERVICIO")
        

        //Filtrar por Id de usuario
        if (!is_null($USERID))
            $sesiones =  $sesiones->where("sesiones.CAJERO", $USERID);

        //fecha
        if (!is_null($fecha_desde)   &&  !is_null($fecha_hasta))
            $sesiones =  $sesiones->where("FECHA_APE", ">=", $fecha_desde)->where("FECHA_APE", "<=", $fecha_hasta);


        $sesiones =  $sesiones->select(
            "sesiones.*",
            DB::raw("FORMAT(EFECTIVO_INI,0,'de_DE') AS EFECTIVO_INICIAL"),
            DB::raw("IF( FORMAT(TOTAL_EFE,0,'de_DE') IS NULL, 0, FORMAT(TOTAL_EFE,0,'de_DE') ) AS TOTAL_EFECTIVO"),
            DB::raw("IF (FORMAT(TOTAL_TAR,0,'de_DE') IS NULL,0,FORMAT(TOTAL_TAR,0,'de_DE') )  AS TOTAL_TARJETA"),
            DB::raw("IF( FORMAT(TOTAL_GIRO,0,'de_DE') IS NULL, 0, FORMAT(TOTAL_GIRO,0,'de_DE') )  AS TOTAL_GIROS"),
            DB::raw("IF( FORMAT(TOTAL_CHEQUE,0,'de_DE') IS NULL, 0 ,  FORMAT(TOTAL_CHEQUE,0,'de_DE')) AS TOTAL_CHEQUES")

            

        );

        $sesiones =   $sesiones->orderBy("REGNRO", "DESC")->groupBy("REGNRO");
        $formato =  request()->header("formato");

        $formato =  is_null($formato) ?  "html"  :   $formato;

        //Si es JSON retornar
        if ($formato == "json") {
            $sesiones =  $sesiones->get();
            return response()->json($sesiones);
        }

        if ($formato ==   "pdf")
            return $this->responsePdf("sesiones.index.grill",  ['SESIONES' => $sesiones->get()], "Sesiones");

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




    public function tieneSesionAbierta($SESIONID = NULL)
    {
        $ses_id = is_null($SESIONID) ? session("ID") :  $SESIONID;
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



    private function enviarArqueoPorEmail($params)
    {
        $adminEmail =  Parametros::where("SUCURSAL", session("SUCURSAL"))->first()->EMAIL_ADMIN;
        $tituloArqueoReporte =  "INFORME DE ARQUEO N° {$params['SESION']->REGNRO}";
        $params =  ['data' => $params,  'view' => "sesiones.arqueo.simple", 'title' =>  $tituloArqueoReporte];
        Mail::to($adminEmail)->send((new GenericEmailSender($params)));
        return response()->json(['ok' => "Se envió por e-mail el informe de arqueo."]);
    }


    public function  totalesArqueo($SESION,  $ARRAY_RETURN = FALSE)
    {

        //Definir formato de retorno 
        $formato =  request()->header("formato");
        $formato =  is_null($formato) ?  "html"  :   $formato;



        if ($formato ==   "json")
            return  response()->json(Sesiones::where("CAJERO", $SESION)->get());

        //Obtener datos de la sesion 
        $sesion =  Sesiones::find($SESION);
        //Todos los ticket expedidos (no anulados)
        $tickets_expedidos = Ventas::join("ventas_det", "ventas_det.VENTA_ID", "=", "ventas.REGNRO")
            ->leftJoin("servicios", "servicios.REGNRO", "=", "ventas.SERVICIO")
            ->where("SESION",  $sesion->REGNRO)
            ->where("CAJERO", $sesion->CAJERO)
            ->where("ESTADO", "<>", "B")
            ->select(
                "ventas.*",
                DB::raw("SUM(ventas_det.CANTIDAD * ventas_det.P_UNITARIO) AS TOTAL_VENTA"),
                "servicios.DESCRIPCION AS SERVICIO",
                DB::raw("if(servicios.COSTO IS NULL, 0, servicios.COSTO) AS COSTO_SERVICIO")
            )
            ->groupBy("ventas.REGNRO")->get();
        //Tickets anulados
        $tickets_anulados = Ventas::where("SESION",  $sesion->REGNRO)
            ->where("CAJERO", $sesion->CAJERO)
            ->where("ESTADO", "B")->get();

        //Totales por servicios de delivery

        $totalesDelivery =    0;
        foreach($tickets_expedidos as $ticket)  $totalesDelivery +=  $ticket->COSTO_SERVICIO;

        //Totales discriminados por forma de pago
        $totales =    Ventas::join("ventas_det", "ventas_det.VENTA_ID", "=", "ventas.REGNRO")
        ->leftJoin("servicios", "servicios.REGNRO", "=", "ventas.SERVICIO")

        ->where("SESION",  $sesion->REGNRO)
        ->where("ventas.ESTADO","<>",  "B")
            ->select(DB::raw("sum(ventas_det.CANTIDAD*ventas_det.P_UNITARIO) AS TOTAL"),
            DB::raw("if(servicios.COSTO IS NULL, 0, servicios.COSTO) AS COSTO_SERVICIO") , "FORMA")
            ->groupBy("FORMA")
            ->get();

        //TARJETA TIGO_MONEY EFECTIVO
        $t_efe = 0;
        $t_tar = 0;
        $t_giro = 0;
        $t_cheque= 0;
        foreach ($totales as  $recaudado) :
            //Efectivo
            if ($recaudado->FORMA == "EFECTIVO")
                $t_efe =  $recaudado->TOTAL + $recaudado->COSTO_SERVICIO;
            else {
                //Tarjeta
                if ($recaudado->FORMA == "TARJETA")
                    $t_tar =  $recaudado->TOTAL + $recaudado->COSTO_SERVICIO;
                else {
                    if ($recaudado->FORMA == "TIGO_MONEY")
                        $t_giro =    $recaudado->TOTAL + $recaudado->COSTO_SERVICIO;
                    elseif ($recaudado->FORMA == "CHEQUE")
                    $t_cheque=     $recaudado->TOTAL + $recaudado->COSTO_SERVICIO;
                }
            }
        endforeach;
        $totalTodo =   $t_efe + $t_tar  + $t_giro + $t_cheque ;
        $totalesVentas = ['TOTAL' => $totalTodo,
         'TOTAL_DELIVERY'=>$totalesDelivery, 
         'EFECTIVO' => $t_efe,  
         'TARJETA' =>  $t_tar,  'TIGO_MONEY' =>   $t_giro, 'CHEQUE'=> $t_cheque];

        //Productos vendidos
        $detalleVentas =  Ventas_det::join("ventas",  "ventas_det.VENTA_ID", "=", "ventas.REGNRO")
        ->where("ventas.ESTADO","<>",  "B")
            ->where("ventas.SESION",  $sesion->REGNRO)
            ->where("ventas.SUCURSAL", $sesion->SUCURSAL)
            ->select("ventas_det.ITEM", DB::raw("sum(ventas_det.P_UNITARIO * ventas_det.CANTIDAD) as IMPORTE"), DB::raw("sum(ventas_det.CANTIDAD) as CANTIDAD "))
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

            if ($formato == "email") {
                try {
                    $this->enviarArqueoPorEmail($parametros);
                    return response()->json(['ok' => "Se ha enviado por email el informe de arqueo."]);
                } catch (Exception $e) {
                    return response()->json(['err' =>  $e->getMessage()]);
                }
            }
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
        $sesion =  Sesiones::find($SesionId);
        $parametros =  $this->totalesArqueo($SesionId, TRUE);

        if (request()->getMethod() == "GET") {
            return view("sesiones.arqueo.index", $parametros);
        } else {

            //Verificar si existen ventas Pendientes de confirmacion
            $ventas_pendientes = Ventas::where("SUCURSAL", $sesion->SUCURSAL)
                ->where("CAJERO", $sesion->CAJERO)->where("ESTADO", "P")->count();

            if ($ventas_pendientes > 0)
                return response()->json(['err' => "No puede cerrarse la Sesión. Antes confirme las ventas pendientes"]);

            try {
                $sesion->fill(

                    [
                        "ESTADO" => "C",
                        "FECHA_CIE" => date("Y-m-d"),
                        "HORA_CIE" => date("H:i"),
                        'TOTAL_EFE' => $parametros['TOTALES']['EFECTIVO'],
                        'TOTAL_TAR' => $parametros['TOTALES']['TARJETA'],
                        "TOTAL_GIRO" => $parametros['TOTALES']['TIGO_MONEY'],
                        "TOTAL_CHEQUE" => $parametros['TOTALES']['CHEQUE']
                    ]
                );
                $sesion->save();

                //ENVIAR POR EMAIL
                $datosDelArqueo = $this->totalesArqueo($SesionId, TRUE);
                request()->session()->forget('SESION');

                try{ 
                    $this->enviarArqueoPorEmail($datosDelArqueo);
                
                   
                    return response()->json(['ok' =>  "Sesión cerrada"]);}
                catch( Exception $ex){
                    return response()->json(['ok' =>  "La sesion ha sido cerrada correctamente. Pero no se ha podido enviar el email correspondiente. Verifique el funcionamiento de correo."]);
                }
               

                //session

            } catch (Exception $e) {
                return response()->json(['err' =>  $e->getMessage()]);
            }
        }
    }
}

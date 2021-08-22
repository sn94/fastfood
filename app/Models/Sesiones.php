<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sesiones extends Model
{
    use HasFactory;

    protected $primaryKey = 'REGNRO';
    public $timestamps = false;
    protected $fillable = [
        'CAJA', 'FECHA_APE', "HORA_APE", "FECHA_CIE", "HORA_CIE", 'TURNO', 'CAJERO', 'EFECTIVO_INI', 'SUCURSAL',


        //Datos de arqueo arrojados por el sistema
        'TOTAL_EFE', 'TOTAL_TAR', "TOTAL_GIRO", "TOTAL_CHEQUE",
        //cifras reales
        'TOTAL_EFE_REAL', 'TOTAL_TAR_REAL', "TOTAL_GIRO_REAL",

        "ESTADO"
    ];


    protected  $dates = ['FECHA_APE',  'FECHA_CIE', "FECHA_CIE", "HORA_CIE"];





    public function PROXIMO_ID()
    {
        $CORRELATIVO_ID = "";

        $CORRELATIVO_ID =  $this->max("REGNRO");
        $CORRELATIVO_ID = is_null($CORRELATIVO_ID) ? 1 : ($CORRELATIVO_ID + 1);
        return  $CORRELATIVO_ID;
    }

   

    public function HORA_APE()
    {
        $millisec =   date("H:i",  strtotime($this->FECHA_APE));
        return  $millisec;
    }

    public function cajero()
    {
        return   $this->hasOne(Usuario::class,  "REGNRO", "CAJERO");
    }
}

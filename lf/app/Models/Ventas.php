<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    use HasFactory;
    

    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [
        'NUMERO','FECHA','CLIENTE'  ,  'ESTADO', 'FORMA', 'CAJERO', 'SUCURSAL', 'IMPORTE_PAGO', 'VUELTO',
        'TAR_CUENTA', 'TAR_BANCO', 'TAR_CEDULA', 'TAR_BOLETA',
        'GIRO_TELEFONO', 'GIRO_CEDULA', 'GIRO_TITULAR', 'GIRO_FECHA',  'TOTAL', 'SESION', 'ORIGEN',
        'DELIVERY', 'SERVICIO'
        
    ];



    protected $dates=[  "FECHA"];

    public function PROXIMO_REGNRO(){
        $CORRELATIVO_ID=  $this->max("REGNRO");
        $CORRELATIVO_ID= is_null($CORRELATIVO_ID) ? 1 :  ( $CORRELATIVO_ID + 1);
        return $CORRELATIVO_ID;
    }


    public function detalle()
    {
        return $this->hasMany(  Ventas_det::class,   'VENTA_ID', 'REGNRO');
    }

    
    public function cajero()
    {
        return $this->hasOne(  Usuario::class,   'REGNRO', 'CAJERO');
    }
    

    public function origen_venta()
    {
        return $this->hasOne(  OrigenVenta::class,   'REGNRO', 'ORIGEN');
    }

    public function cliente()
    {
        return $this->hasOne(  Clientes::class,   'REGNRO', 'CLIENTE');
    }




}

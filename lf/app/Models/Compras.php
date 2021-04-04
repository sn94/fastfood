<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compras extends Model
{
    use HasFactory;

    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = ['NUMERO', 'CONDICION', 'FECHA', 'PROVEEDOR',  
    'CONCEPTO', 'SUCURSAL', 'FORMA_PAGO', 'REGISTRADO_POR','NPEDIDO_ID'  ];

    protected $dates=[
        "FECHA"
    ];


    public function  TOTAL()
    {
        $detalle =  $this->compras_detalle;
        $total = 0;
        foreach ($detalle as $detail) :
            $subtotal =  $detail->CANTIDAD  *  $detail->P_UNITARIO;
            $total +=  $subtotal;
        endforeach;
        return  round($total);
    }



    
    public function compras_detalle()
    {
        return $this->hasMany(Compras_detalles::class, 'COMPRA_ID',  'REGNRO');
    }

    public function proveedor()
    {
        return $this->hasOne(Proveedores::class,  "REGNRO", "PROVEEDOR");
    }

    public function sucursal()
    {
        return $this->hasOne(Sucursal::class,  "REGNRO", "SUCURSAL");
    }
}

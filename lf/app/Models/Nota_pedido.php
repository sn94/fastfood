<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota_pedido extends Model
{
    use HasFactory;

    protected $table = "nota_pedido_matriz";
    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [
        'FECHA',  'CONCEPTO', 'ESTADO',  'SUCURSAL', 'REGISTRADO_POR', 'SOLICITADO_POR',
        'OBSERVACION',
        'RECIBIDO_POR'
    ];

 

    protected $dates = [
        "FECHA",
        'created_at',
        'updated_at'
    ];





    public function nota_pedido_detalles()
    {
        return $this->hasMany(Nota_pedido_detalles::class, 'NPEDIDO_ID',  'REGNRO');
    }


    public function registrado_por()
    {
        return   $this->hasOne(Usuario::class,  "REGNRO", "REGISTRADO_POR");
    }


    
   
    
    public function recibido_por()
    {
        return   $this->hasOne(Usuario::class,  "REGNRO", "RECIBIDO_POR");
    }

}

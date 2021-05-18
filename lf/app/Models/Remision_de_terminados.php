<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remision_de_terminados extends Model
{
    use HasFactory;

    protected $table = "remi_produ_terminados";
    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [
        'NUMERO',  'FECHA',  'CONCEPTO', 'ESTADO',  'SUCURSAL',
        'REGISTRADO_POR',  'PRODUCCION_ID', 'AUTORIZADO_POR'
    ];


    protected $dates = [
        "FECHA"
    ];

    public function remision_detalle()
    {
        return $this->hasMany(Remision_de_terminados_detalle::class, 'REMISION_ID',  'REGNRO');
    }
}

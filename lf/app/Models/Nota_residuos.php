<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota_residuos extends Model
{
    use HasFactory;

    protected $table = "nota_residuos";
    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [
        'NUMERO',  'FECHA',  'CONCEPTO', 'ESTADO',  'SUCURSAL',
        'REGISTRADO_POR',  'PRODUCCION_ID'
    ];



    public function residuos_detalle()
    {
        return $this->hasMany(Nota_residuos_detalle::class, 'NRESIDUO_ID',  'REGNRO');
    }
}

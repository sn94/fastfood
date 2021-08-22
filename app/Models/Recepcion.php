<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recepcion extends Model
{
    use HasFactory;

    protected $table = "recepcion";
    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = ['NUMERO',  'FECHA',  'CONCEPTO', 'ESTADO',  'SUCURSAL',
'REGISTRADO_POR'];



    public function recepcion_detalle()
    {
        return $this->hasMany(Recepcion_detalles::class, 'RECEPCION_ID',  'REGNRO');
    }
}

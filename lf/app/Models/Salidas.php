<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salidas extends Model
{
    use HasFactory;

    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [
        'NUMERO', 'FECHA', 'SUCURSAL', 'TIPO_SALIDA',  'DESTINO', 'SUCURSAL_DESTINO', 'REGISTRADO_POR', 'ACTUALIZADO_POR',
        'AUTORIZADO_POR',  'CONCEPTO', 'PRODUCCION_ID', 'PEDIDO_ID'
    ];





    public function salidas_detalle()
    {
        return $this->hasMany(Salidas_detalles::class, 'SALIDA_ID',  'REGNRO');
    }
}

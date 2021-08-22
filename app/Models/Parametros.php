<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametros extends Model
{
    use HasFactory;

    protected $primaryKey = 'REGNRO';
    public $timestamps = false;
    protected $fillable = [
        'MENSAJE_TICKET',
        'DESCONTAR_MP_EN_VENTA',
        'EMAIL_ADMIN',
        'RAZON_SOCIAL', 
        'DOMICILIO_COMERCIAL',
        "TELEFONO_COMERCIAL",
        "RUC",
        "TITULO_TICKET",
        "NRO_COPIAS"
    ];
}

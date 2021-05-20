<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ficha_produccion extends Model
{
    use HasFactory;

    protected $table = "ficha_produccion";
    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = ['FECHA', 'ESTADO',  'SUCURSAL', 'REGISTRADO_POR', 'RECIBIDO_POR', 'FINALIZADO_POR', 
     'ACTUALIZADO_POR', 'ELABORADO_POR'  ];



     
    protected $dates = [
        "FECHA",
        'created_at',
        'updated_at'
    ];



    public function detalle_produccion()
    {
        return $this->hasMany(Ficha_produccion_detalles::class, 'PRODUCCION_ID',  'REGNRO');
    }

    public function registrador()
    {
        return $this->belongsTo(Usuario::class,  'REGISTRADO_POR', 'REGNRO'); //foreign key   owner key
    }

    public function despachador()
    {
        return $this->belongsTo(Usuario::class,  'RECIBIDO_POR', 'REGNRO'); //foreign key   owner key
    }

     
}

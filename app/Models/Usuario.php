<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected  $table = "usuarios";
    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = ['USUARIO', 'PASS', 'NIVEL', 'ESTADO', 'NOMBRES', 'CEDULA', 'SUCURSAL', 'CARGO', 'TURNO', 
    'CELULAR', 'EMAIL', 'ORDEN'];




    public function sucursal()
    {
        return $this->belongsTo( Sucursal::class,  'SUCURSAL'  ); 
    }



}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicios extends Model
{
    use HasFactory;
    
    protected  $table= "servicios";
    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [  'DESCRIPCION', 'COSTO', 'ORDEN' ];

 
}

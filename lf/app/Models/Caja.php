<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;
    
    protected  $table= "cajas";
    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [  'DESCRIPCION' ];

 
}

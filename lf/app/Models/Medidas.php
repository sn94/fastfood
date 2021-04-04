<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medidas extends Model
{
    use HasFactory;
    
    protected  $table= "unimed";
    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [ 'UNIDAD', 'DESCRIPCION' ];

 
}

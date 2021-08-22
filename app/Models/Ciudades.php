<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciudades extends Model
{
    use HasFactory;
    
  
    protected $primaryKey = 'regnro';
    public $timestamps = false;
    protected $fillable = [  'departa',   'ciudad',  'ORDEN'];


    public function  departamento(){
        return  
        $this->belongsTo(  Ciudades_departa::class,   "departa",  "regnro");
    }
 
}

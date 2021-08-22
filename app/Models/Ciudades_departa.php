<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciudades_departa extends Model
{
    use HasFactory;
    
  
    protected $table= "ciudades_departa";
    protected $primaryKey = 'regnro';
    public $timestamps = false;
    protected $fillable = [  'departa' ];

 

    public function ciudades(){
       return  $this->hasMany( Ciudades::class,  "departa", "regnro");
    }
}

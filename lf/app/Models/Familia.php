<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    use HasFactory;
     
    protected  $table= "familia";
    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [  'DESCRIPCION',  'NRO_PESTANA' , 'MOSTRAR_EN_VENTA'];

 


    public function productos(){
        return $this->hasMany( Stock::class, "FAMILIA", "REGNRO");
    }



}

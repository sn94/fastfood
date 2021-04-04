<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametros extends Model
{
    use HasFactory;
     
    protected $primaryKey = 'REGNRO';
    public $timestamps = false;
    protected $fillable = [  'MENSAJE_TICKET' ];

 
}

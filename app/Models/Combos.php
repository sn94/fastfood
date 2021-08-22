<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combos extends Model
{
    use HasFactory;
   
    protected $table= "stock_combos";
    protected $primaryKey = 'REGNRO';
    public $timestamps = false;
    protected $fillable = [
        'STOCK_ID','COMBO_ID','CANTIDAD'
    ];


    
    public function stock()
    {
        return $this->belongsTo( Stock::class, 'STOCK_ID' ,  'REGNRO' ); 
    }


}

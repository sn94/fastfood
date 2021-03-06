<?php

namespace App\Http\Requests;

use App\Helpers\Utilidades;
use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
{


     

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    protected function prepareForValidation()
    {

        $this->merge([
          
            'PVENTA' =>   Utilidades::limpiar_numero($this->PVENTA), // Utilidades::limpiar_numero()
            'PVENTA_MITAD' =>   Utilidades::limpiar_numero($this->PVENTA_MITAD),
            'PVENTA_EXTRA' =>   Utilidades::limpiar_numero($this->PVENTA_EXTRA),
            'PCOSTO' =>   Utilidades::limpiar_numero($this->PCOSTO),
            'STOCK_MAX' =>   Utilidades::limpiar_numero($this->STOCK_MAX),
            'STOCK_MIN' =>   Utilidades::limpiar_numero($this->STOCK_MIN),
            //Cantidad por ingrediente
            'CANTIDAD' => Utilidades::limpiar_numero($this->CANTIDAD),
            //combo
            'COMBO_CANTIDAD' => Utilidades::limpiar_numero($this->COMBO_CANTIDAD)
            
        ]);
    }
}

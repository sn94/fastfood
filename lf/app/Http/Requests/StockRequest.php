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
            'PVENTA' =>   Utilidades::limpiar_numero($this->PVENTA),//
            'PVENTA_MITAD' =>   Utilidades::limpiar_numero($this->PVENTA_MITAD),
            'PCOSTO' =>   Utilidades::limpiar_numero($this->PCOSTO),
            'STOCK_MAX' =>   Utilidades::limpiar_numero($this->STOCK_MAX),
            'STOCK_MIN' =>   Utilidades::limpiar_numero($this->STOCK_MIN),
            //Cantidad por ingrediente
            'CANTIDAD' => Utilidades::limpiar_numero( $this->CANTIDAD), 
            //Variedad de precios
            'PRECIO_ENTERO' => Utilidades::limpiar_numero( $this->PRECIO_ENTERO),
            'PRECIO_MITAD' => Utilidades::limpiar_numero( $this->PRECIO_MITAD),
            'PRECIO_PORCION' => Utilidades::limpiar_numero( $this->PRECIO_PORCION )
        ]);
    }
}

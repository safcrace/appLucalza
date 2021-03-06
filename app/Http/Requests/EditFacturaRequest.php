<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditFacturaRequest extends Request
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
        return [
            'TIPOGASTO_ID' => 'required',
            'subcategoriaTipoGasto' => 'required',
            'PROVEEDOR_ID' => 'required',
            'FOTO'    => 'mimes:jpeg,bmp,png',           
            'TIPODOCUMENTO_ID' => 'required',
            'FECHA_FACTURA' => 'required',            
            'NUMERO' => 'required',
            'TOTAL' => 'required',
            'KM_INICIO' => 'numeric',
            'KM_FINAL' => 'numeric',
            'CANTIDAD_PORCENTAJE_CUSTOM' => 'numeric'
        ];
    }

    public function messages()
    {
        return [
            'TIPOGASTO_ID.required' => 'El campo Categoria Gasto es obligatorio',
        ];
    }    
}

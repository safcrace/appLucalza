<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateTasaCambioRequest extends Request
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
            'COMPRA'    => 'required',
        ];
    }

    /**
     * Set custom messages for validator errors.
     *
     * @return array

    public function messages()
    {
        return [
            'FECHA.unique' => 'No se puede ingresar dos tipos de cambio con la misma fecha',
        ];
    }
     * */
}

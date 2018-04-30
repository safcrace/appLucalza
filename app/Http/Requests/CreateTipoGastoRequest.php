<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateTipoGastoRequest extends Request
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
            'GRUPO_ID' => 'required',
            'DESCRIPCION'    => 'required',
            'OPCIONCOMBUSTIBLE_ID'    => 'required',
            'CUENTA_EXENTO' => 'required',
            'CODIGO_IMPUESTO_EXENTO' => 'required',
            'CUENTA_AFECTO' => 'required',
            'CODIGO_IMPUESTO_AFECTO' => 'required',
            'CUENTA_REMANENTE' => 'required',
            'CODIGO_IMPUESTO_REMANENTE' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'DESCRIPCION.required' => 'El campo Descripción es Obligatorio.',
        ];
    }
}

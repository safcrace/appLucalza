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
            'DESCRIPCION'    => 'required',
            'EXENTO'    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'DESCRIPCION.required' => 'El campo Descripción es Obligatorio.',
        ];
    }
}

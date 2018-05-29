<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreatePresupuestoRequest extends Request
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
            'VIGENCIA_INICIO'    => 'required',
            'VIGENCIA_FINAL'    => 'required',
            'USUARIO_ID' => 'required',
            'RUTA_ID' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'RUTA_ID.required' => 'El campo Ruta es obligatorio',
        ];
    }
}

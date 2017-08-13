<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateRutaRequest extends Request
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
            'CLAVE'    => 'required|unique:cat_ruta|max:3',
            'DESCRIPCION'    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'CLAVE.unique' => 'El Código Ingresado Ya Existe!!',
        ];
    }
}

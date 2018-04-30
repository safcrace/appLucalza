<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateUsuarioRequest extends Request
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
            'nombre'    => 'required',
            'tel_codpais'    => 'digits_between:2,4',  
            'telefono'  => 'required',
            'email' => 'required|unique:users',
            'activo' => 'required',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'El Correo Electrónico Ingresado Ya Existe!!',
        ];
    }
}

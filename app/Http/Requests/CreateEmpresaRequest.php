<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateEmpresaRequest extends Request
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
            'CLAVE'    => 'required|max:3',
            'DESCRIPCION'    => 'required',
            'LICENSESERVER'    => 'required',
            'USERSAP'    => 'required',
            'PASSSAP'    => 'required',
            'DBSAP'    => 'required',
            'USERSQL'    => 'required',
            'PASSSQL'    => 'required',
            'SERVIDORSQL'    => 'required',
            'SAPDBTYE'    => 'required',
        ];
    }
}

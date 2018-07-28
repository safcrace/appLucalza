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
            'MONEDA_LOCAL' => 'required',
            'MONEDA_SYS' => 'required',
            'LICENSESERVER'    => 'required',
            'USERSAP'    => 'required',
            'FILAS_NOTA_CREDITO' => 'required',
            'PASSSAP'    => 'required',
            'DBSAP'    => 'required',
            'USERSQL'    => 'required',
            'PASSSQL'    => 'required',
            'SERVIDORSQL'    => 'required',
            'ID_DATASERVERTYPE'    => 'required',
            'TIEMPOATRASO_RUTAS'    => 'required',
            'TIEMPOATRASO_OTROSGASTOS'    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'CLAVE.required' => 'El campo Código Empresa es Obligatorio.',
            'DESCRIPCION.required' => 'El campo Nombre Empresa es Obligatorio.',
        ];
    }
}

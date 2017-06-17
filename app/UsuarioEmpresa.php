<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioEmpresa extends Model
{
    protected $table = 'cat_usuarioempresa';

    protected $fillable = ['USER_ID', 'EMPRESA_ID', 'CODIGO_PROVEEDOR', 'ANULADO'];

    public $timestamps = false;

}

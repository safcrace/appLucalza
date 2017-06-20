<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioRuta extends Model
{
    protected $table = 'cat_usuarioruta';

    protected $fillable = ['USER_ID', 'RUTA_ID', 'ANULADO'];

    public $timestamps = false;
}

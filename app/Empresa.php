<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'cat_empresa';

    protected $fillable = ['CLAVE', 'DESCRIPCION', 'LICENSESERVER', 'USERSAP', 'PASSSAP', 'DBSAP', 'USERSQL',
                           'PASSSQL', 'SERVIDORSQL', 'SAPDBTYPE', 'ANULADO'];

    public $timestamps = false;


    public function users(){
        return $this->belongsToMany(User::class,  'cat_usuarioempresa');
    }

    public function rutas(){
        return $this->belongsToMany(Ruta::class,  'cat_ruta');
    }
}

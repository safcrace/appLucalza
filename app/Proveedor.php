<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cat_proveedor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['EMPRESA_ID', 'MONEDA_ID', 'IDENTIFICADOR_TRIBUTARIO', 'CLAVE', 'DESCRIPCION', 'ANULADO'];


    public $timestamps = false;


    public function empresas(){
        return $this->belongsToMany(Empresa::class); ///Ojo porque debo modificar
    }
}

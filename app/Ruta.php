<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cat_ruta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['EMPRESA_ID', 'CLAVE', 'DESCRIPCION', 'ANULADO'];

    
    public $timestamps = false;


    public function empresas(){
        return $this->belongsToMany(Empresa::class);
    }
}

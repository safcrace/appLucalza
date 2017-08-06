<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoAsignacion extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cat_tipoasignacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['DESCRIPCION', 'ANULADO'];


    public $timestamps = false;
}

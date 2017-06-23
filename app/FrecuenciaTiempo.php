<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FrecuenciaTiempo extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cat_frecuenciatiempo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['CLAVE', 'DESCRIPCION'];


    public $timestamps = false;
}

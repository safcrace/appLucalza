<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SapDbType extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sap_dataservertype';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ID_DATASERVERTYPE', 'DESCRIPCION'];


    public $timestamps = false;
}

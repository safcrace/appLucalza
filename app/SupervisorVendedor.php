<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupervisorVendedor extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cat_supervisor_vendedor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['SUPERVISOR_USUARIO_ID', 'VENDEDOR_USUARIO_ID'];


    public $timestamps = false;
}

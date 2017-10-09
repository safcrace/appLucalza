<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CuentasContables extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cat_cuentasContables';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'name'];


    public $timestamps = false;
}

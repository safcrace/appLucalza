<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Moneda extends Model
{
    protected $table = 'cat_moneda';

    protected $fillable = ['CLAVE', 'DESCRIPCION', 'ANULADO'];

    protected $guarded = [];

    public $timestamps = false;

    public function tasaCambios(){
        return $this->hasMany(TasaCambio::class);
    }




}

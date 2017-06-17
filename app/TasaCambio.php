<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TasaCambio extends Model
{
    protected $table = 'cat_tasacambio';

    protected $fillable = ['MONEDA_ID', 'FECHA', 'COMPRA', 'VENTA', 'PROMEDIO', 'ANULADO'];

    public $timestamps = false;

    public function moneda(){
    	return $this->belongsTo(Moneda::class);
    }



}

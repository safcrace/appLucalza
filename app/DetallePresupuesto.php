<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetallePresupuesto extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pre_detpresupuesto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['PRESUPUESTO_ID', 'TIPOGASTO_ID', 'MONTO', 'CENTROCOSTO1', 'CENTROCOSTO2', 'CENTROCOSTO3',
                           'CENTROCOSTO4', 'CENTROCOSTO5', 'ANULADO'];


    public $timestamps = false;

    public function presupuesto(){
    	return $this->belongsTo(Presupuesto::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pre_presupuesto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['USUARIORUTA_ID', 'MONEDA_ID', 'FRECUENCIATIEMPO_ID', 'VIGENCIA_INICIO', 'VIGENCIA_FINAL', 'DESCRIPCION', 'ANULADO'];


    public $timestamps = false;

    public function detallePresupuestos(){
        return $this->hasMany(DetallePresupuesto::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'liq_liquidacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['USUARIORUTA_ID', 'ESTADOLIQUIDACION_ID', 'FECHA_INICIO', 'FECHA_FINAL', 'ES_LOCAL', 'COMENTARIO', 'SUPERVISOR_AUTORIZACION',
                           'SUPERVISOR_COMENTARIO', 'SUPERVISOR_FECHA', 'CONTABILIDAD_AUTORIZACION', 'CONTABILIDAD_COMENTARIO', 'CONTABILIDAD_FECHA', 'ANULADO'];


    public $timestamps = false;

    public function facturas(){
        return $this->hasMany(Factura::class);
    }
}

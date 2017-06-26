<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'liq_factura';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['LIQUIDACION_ID', 'DETPRESUPUESTO_ID', 'MONEDA_ID', 'PROVEEDOR_ID', 'CAUSAEXENCION_ID', 'SERIE', 'NUMERO', 'FECHA_FACTURA', 'MONTO_AFECTO',
                           'MONTO_EXENTO', 'MONTO_IVA', 'MONTO_REMANENTE', 'CANTIDAD_PORCENTAJE_CUSTOM', 'TOTAL', 'DOCENTRY_SAP_FACTURA', 'DOCENTRY_SAP_NC',
                           'FOTO', 'ANULADO'];


    public $timestamps = false;

    protected $dates = ['FECHA_FACTURA'];

    public function liquidacion(){
      return $this->belongsTo(Liquidacion::class);
    }
}

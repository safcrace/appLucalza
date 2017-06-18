<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoGasto extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cat_tipogasto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['EMPRESA_ID', 'CAUSAEXENCION_ID', 'DESCRIPCION', 'EXENTO', 'MONTO_A_APLICAR', 'CUENTA_CONTABLE_EXENTO', 'CODIGO_IMPUESTO_EXENTO',
                           'CUENTA_CONTABLE_AFECTO', 'CODIGO_IMPUESTO_AFECTO', 'CUENTA_CONTABLE_REMANENTE', 'CODIGO_IMPUESTO_REMANENTE', 'ANULADO'];


    public $timestamps = false;


    public function empresas(){
        return $this->belongsToMany(Empresa::class); ///Ojo porque debo modificar
    }
}

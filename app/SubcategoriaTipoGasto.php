<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubcategoriaTipoGasto extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cat_subcategoria_tipogasto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['TIPOGASTO_ID', 'CAUSAEXENCION_ID', 'DESCRIPCION', 'EXENTO', 'MONTO_A_APLICAR', 'ANULADO'];


    public $timestamps = false;

}

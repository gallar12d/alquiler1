<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustodiaDetalle extends Model
{
    //
    use SoftDeletes;
    protected $table = 'custodia_detalle';    
    protected $primaryKey = 'id_custodia_detalle';
   

    
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abono extends Model
{
    //

    use SoftDeletes;
    protected $table = 'abono';    
    protected $primaryKey = 'id_abono';
    
     public function factura()
    {
        return $this->hasOne('App\Factura', 'id_factura', 'id_factura');
    }
   

    
}

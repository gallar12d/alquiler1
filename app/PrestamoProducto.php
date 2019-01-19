<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrestamoProducto extends Model
{
    //
      use SoftDeletes;
    protected $table = 'prestamo_producto';

    protected $primaryKey = 'id';
    
     public function producto()
    {
        return $this->hasOne('App\Producto', 'id', 'id_producto');
    }
    
     public function sede()
    {
        return $this->hasOne('App\Sede', 'id', 'id_sede');
    }

 

   
}

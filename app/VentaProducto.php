<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VentaProducto extends Model
{
    //
      use SoftDeletes;
    protected $table = 'venta_producto';

    protected $primaryKey = 'id';

 

   
}

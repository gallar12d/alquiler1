<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsecutivoFactura extends Model
{
    //
    protected $table = 'consecutivo_factura';
    protected $fillable = [ 'numero'];
    protected $guarded = ['id_consecutivo'];
    protected $primaryKey = 'id_consecutivo';
}

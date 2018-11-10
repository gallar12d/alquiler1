<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factura extends Model
{
    //
    use SoftDeletes;
    protected $table = 'factura';
    protected $fillable = [ 'id_factura', 'abono', 'numero_factura', 'cedula', 'valor', 'saldo', 'estado', 'fecha', 'fecha_pago', 'metodo_pago'];
    protected $guarded = ['id_factura'];
    protected $primaryKey = 'id_factura';
    protected $dates = ['deleted_at'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    //
      use SoftDeletes;
    protected $table = 'producto';
    protected $fillable = [ 'nombre', 'talla', 'color', 'referencia', 'estado', 'valor', 'valor_venta', 'foto', 'linea', 'id_proveedor', 'fecha_entrega', 'fecha_devolucion'];
    protected $guarded = ['id'];
    protected $primaryKey = 'id';

    public function proveedor()
    {
        return $this->hasOne('App\Proveedor', 'id_proveedor', 'id_proveedor');
    }

   
}

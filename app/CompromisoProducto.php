<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class CompromisoProducto extends Model
{
    //
    
    protected $table = 'compromiso_producto';
    protected $fillable = [ 'id_producto', 
    'id_compromiso', 'ajustes'] ;

    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    public function producto()
    {
        return $this->hasOne('App\Producto', 'id', 'id_producto');
    }

    public function compromiso(){
        return $this->hasOne('App\Compromiso', 'id', 'id_compromiso');

    }
}

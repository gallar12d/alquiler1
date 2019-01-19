<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compromiso extends Model
{
    //

    use SoftDeletes;
    protected $table = 'compromiso';
    protected $fillable = [ 'fecha_compromiso', 
    'fecha_devolucion', 'ajustes', 'id_garantia', 'id_factura', 'cedula', 'estado', 'buen_estado', 'condiciones_entrega'] ;

    protected $primaryKey = 'id_compromiso';
    protected $dates = ['deleted_at'];

    public function persona()
    {
        return $this->hasOne('App\User', 'cedula', 'cedula');
    }
    public function factura()
    {
        return $this->hasOne('App\Factura', 'id_factura', 'id_factura');
    }

    public function compromisoproducto(){
        return $this->hasMany('App\Compromisoproducto', 'id_compromiso', 'id_compromiso');
    }
    
    public function abonos(){
        return $this->hasMany('App\Abono', 'id_compromiso', 'id_compromiso');
    }
}

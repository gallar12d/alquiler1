<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    //
     use SoftDeletes;
    protected $table = 'users';
    protected $fillable = [ 'name', 
    'cedula', 'direccion', 'sexo', 'celular', 'telefono',
     'telefono_emergencia', 'email', 'actividad', 
     'referencia_nombre', 'referencia_celular', 'tipo', 'password', 'pass', 'rol'] ;
    protected $guarded = ['id', 'password', 'remember_token', 'pass', 'rol'];

   
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salida extends Model
{
    //
    use SoftDeletes;
    protected $table = 'salida';
    protected $fillable = [  'concepto', 'valor', 'nombre_persona', 'identificacion'];
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
}

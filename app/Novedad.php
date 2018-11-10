<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Novedad extends Model
{
    //
    use SoftDeletes;
    protected $table = 'novedad';
    protected $fillable = [  'descripcion', 'fecha'];
    
    protected $primaryKey = 'id';
    
}

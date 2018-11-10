<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sede extends Model
{
    //
    use SoftDeletes;
    protected $table = 'Sede';
    protected $fillable = [  'nombre'];
    
    protected $primaryKey = 'id';
    
}

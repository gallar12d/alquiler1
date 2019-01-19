<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Base extends Model
{
    //

    use SoftDeletes;
    protected $table = 'base';    
    protected $primaryKey = 'id_base';
   

    
}

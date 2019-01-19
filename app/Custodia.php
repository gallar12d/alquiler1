<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Custodia extends Model
{
    //

    use SoftDeletes;
    protected $table = 'custodia';    
    protected $primaryKey = 'id_custodia';
   

    
}

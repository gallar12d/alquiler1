<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abonocaja extends Model
{
    //

    use SoftDeletes;
    protected $table = 'abonocaja';    
    protected $primaryKey = 'id_abonocaja';
   

    
}

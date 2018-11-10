<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entrada extends Model
{
    //
    use SoftDeletes;
    protected $table = 'entrada';
    protected $fillable = [  'concepto', 'valor', 'tipo'];
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
}

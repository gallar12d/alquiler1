<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Garantia extends Model
{
    //
    use SoftDeletes;
    protected $table = 'garantia';
    protected $fillable = [ 'tipo_garantia', 'valor', 'fecha_pago'];
    protected $guarded = ['id_garantia'];
    protected $primaryKey = 'id_garantia';
    protected $dates = ['deleted_at'];
}

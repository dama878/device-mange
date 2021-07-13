<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'devices';
    protected $primaryKey = 'DEV_ID';
    public $timestamps = false;
    public function type(){
        return $this->belongsTo(Type::class,'TYPE_ID');
    }
}

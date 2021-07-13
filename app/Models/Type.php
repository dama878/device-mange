<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'types';
    protected $primaryKey = 'TYPE_ID';
    public $timestamps = false;

    public function devices(){
        return $this->hasMany(Device ::class);
    }
}

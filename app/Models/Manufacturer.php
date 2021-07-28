<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $table = 'manufacturers';
    protected $primaryKey = 'MAN_ID';
    public $timestamps = false;

    public function devices(){
        return $this->hasMany(Device ::class);
    }
}

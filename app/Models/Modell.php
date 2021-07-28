<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modell extends Model
{
    protected $table = 'models';
    protected $primaryKey = 'MOD_ID';
    public $timestamps = false;

    public function borrows(){
        return $this->belongsToMany(Borrow::class,'borrow_detail_models','MOD_ID','BOR_ID');
    }

    public function borrow_detail_models(){
        return $this->hasMany(BorrowDetailModel::class);
    }

    public function device(){
        return $this->belongsTo(Device::class);
    }
}



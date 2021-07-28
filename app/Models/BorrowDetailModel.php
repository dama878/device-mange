<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowDetailModel extends Model
{
    use HasFactory;
    protected $table = 'borrow_detail_models';
    protected $primaryKey = 'BORDE_ID';
    public $timestamps = false;

    public function model(){
        return $this->belongsToMany(Modell::class,'borrow_detail_models','MOD_ID','BOR_ID');
    }

    public function borrow(){
        return $this->belongsToMany(Borrow::class,'borrow_detail_models','MOD_ID');
    }

    public function borrow_return(){
        return $this->belongsTo(BorrowReturn::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;
    protected $table = 'borrows';
    protected $primaryKey = 'BOR_ID';
    public $timestamps = false;

    public function models(){
        return $this->belongsToMany(Modell::class,'borrow_detail_models','BOR_ID','MOD_ID');
    }

    public function borrow_detail_models(){
        return $this->hasMany(BorrowDetailModel::class);
    }

    public function borrowers(){
        return $this->belongsTo(Borrower::class);
    }
}

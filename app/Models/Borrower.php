<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    protected $table = 'borrowers';
    protected $primaryKey = 'BORROWER_ID';
    public $timestamps = false;

    public function borrower_group()
    {
        return $this->belongsTo(BorrowerGroup::class, 'BOGROUP_ID');
    }

    public function borrow_return()
    {
        return $this->hasMany(BorrowReturn::class);
    }
    public function fines()
    {
        return $this->hasMany(Fine::class);
    }
    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}
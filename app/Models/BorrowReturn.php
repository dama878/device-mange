<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowReturn extends Model
{
    protected $table = 'borrow_returns';
    protected $primaryKey = 'BORETURN_ID';
    public $timestamps = false;

    public function borrowers()
    {
        return $this->belongsTo(Borrower::class, 'BORROWER_ID');
    }
}
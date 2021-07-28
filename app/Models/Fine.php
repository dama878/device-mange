<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $table = 'fines';
    protected $primaryKey = 'FINE_ID';
    public $timestamps = false;

    public function borrower()
    {
        return $this->belongsTo(Borrower::class, 'BORROWER_ID');
    }
}
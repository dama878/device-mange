<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowerGroup extends Model
{
    protected $table = 'borrower_groups';
    protected $primaryKey = 'BOGROUP_ID';
    public $timestamps = false;

    public function borrowers()
    {
        return $this->hasMany(Borrower::class);
    }
}
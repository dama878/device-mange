<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $table = 'imports';
    protected $primaryKey = 'IMP_ID';
    public $timestamps = false;

    public function importDetail()
    {
        return $this->hasMany(ImportDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CUS_ID');
    }
}

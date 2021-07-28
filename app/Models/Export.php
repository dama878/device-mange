<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    protected $table = 'exports';
    protected $primaryKey = 'EXP_ID';
    public $timestamps = false;

    public function exportDetail()
    {
        return $this->hasMany(ExportDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CUS_ID');
    }
}

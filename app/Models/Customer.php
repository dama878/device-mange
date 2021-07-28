<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'CUS_ID';
    public $timestamps = false;

    public function export()
    {
        return $this->hasMany(Export::class);
    }

    public function import()
    {
        return $this->hasMany(Import::class);
    }
}

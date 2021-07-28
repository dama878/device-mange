<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportDetail extends Model
{
    protected $table = 'import_details';
    protected $primaryKey = 'IMPDE_ID';
    public $timestamps = false;

    public function import()
    {
        return $this->belongsTo(Import::class, 'IMP_ID');
    }

    public function model()
    {
        return $this->belongsTo(Model::class, 'MOD_ID');
    }
}

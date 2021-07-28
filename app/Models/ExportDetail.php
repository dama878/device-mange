<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportDetail extends Model
{
    protected $table = 'export_details';
    protected $primaryKey = 'EXPDE_ID';
    public $timestamps = false;

    public function export()
    {
        return $this->belongsTo(Export::class, 'EXP_ID');
    }

    public function model()
    {
        return $this->belongsTo(Model::class, 'MOD_ID');
    }
}

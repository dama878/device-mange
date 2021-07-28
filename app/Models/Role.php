<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'ROLE_ID';
    public $timestamps = false;

    public function users(){
        return $this->hasMany(User ::class);
    }
    public function perrmissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_roles');
    }
}

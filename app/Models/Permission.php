<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $primaryKey = 'PERMISSION_ID';
    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_roles');
    }
}
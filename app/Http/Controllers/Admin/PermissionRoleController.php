<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PermissionRoleController extends Controller
{
    public function index()
    {
        return view('admin.permissionrole');
    }
}
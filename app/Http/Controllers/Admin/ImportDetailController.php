<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportDetailController extends Controller
{
    public function index()
    {
        return view('admin.importdetail');
    }
}

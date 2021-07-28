<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use App\Models\Type;

class DeviceController extends Controller
{
    public function index() {
        $type = Type::all();
        $typeNames = $type->where('IsDeleted', 0)->sortBy('TypeName')->pluck('TypeName')->unique();

        $manufacturer = Manufacturer::all();
        $manNames = $manufacturer->where('IsDeleted', 0)->sortBy('ManName')->pluck('ManName')->unique();
        return view('admin.device',compact('typeNames','manNames'));
    }
}

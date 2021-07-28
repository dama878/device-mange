<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Http\Responses\BaseResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PermissionsController extends Controller
{
    public function get($id = null)
    {
        $data = null;
        if ($id) {
            $data = Permission::where(['IsDeleted' => 0, 'PERMISSION_ID' => $id])->first();
        } else {
            $data = collect();
            $rows = Permission::where('IsDeleted', 0)
                ->where(function ($query) {
                    $query->whereNull('PARENT_ID')->orWhere('PARENT_ID', 0);
                })
                ->orderBy('Name', 'asc')
                ->get();
            foreach ($rows as $value) {
                $data->push($value);
                $level1 = Permission::where(['IsDeleted' => 0, 'PARENT_ID' => $value->PERMISSION_ID])
                    ->orderBy('Name', 'asc')->get();
                foreach ($level1 as $rowLevel1) {
                    $data->push($rowLevel1);
                    $level2 = Permission::where(['IsDeleted' => 0, 'PARENT_ID' => $rowLevel1->PERMISSION_ID])
                        ->orderBy('Name', 'asc')->get();
                    foreach ($level2 as $rowLevel2) {
                        $data->push($rowLevel2);
                    }
                }
            }
        }
        return BaseResult::withData($data);
    }
    public function getParentList()
    {
        $permission = collect();
        $rows = Permission::where('IsDeleted', 0)
            ->where(function ($query) {
                $query->whereNull('PARENT_ID')->orWhere('PARENT_ID', 0);
            })
            ->orderBy('Name', 'asc')
            ->get();
        foreach ($rows as $value) {
            $permission->push($value);
            $level1 = Permission::where(['IsDeleted' => 0, 'PARENT_ID' => $value->PERMISSION_ID])
                ->orderBy('Name', 'asc')->get();
            foreach ($level1 as $rowLevel1) {
                $permission->push($rowLevel1);
            }
        }
        return BaseResult::withData($permission);
    }
    public function add(Request $request)
    {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
            'Name' => 'required'

        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $permission = new Permission();
            try {
                $parentId = $request->PARENT_ID;
                $permission->PARENT_ID = $parentId;
                // -- depth -------
                if ($parentId == 0) {
                    $permission->Depth = 0;
                } else {
                    $parent = Permission::find($parentId);
                    if ($parent) {
                        $permission->Depth = $parent->Depth + 1;
                    } else {
                        $permission->Depth = 0;
                    }
                }
                // -- end: depth --
                $permission->Name = $request->Name;
                $permission->IsDeleted = 0;
                $permission->CreatedDate = now();
                $permission->CreatedBy = $user->USE_ID;

                $permission->save();
                return BaseResult::withData($permission);
            } catch (\Exception $e) {
                return BaseResult::error(500, $e->getMessage());
            }
        }
    }
    public function update(Request $request)
    {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
            'Name' => 'required'

        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $permission = Permission::find($request->input('id'));
            if ($permission) {
                try {
                    $parentId = $request->PARENT_ID;
                    $permission->PARENT_ID = $parentId;
                    // -- depth -------
                    if ($parentId == 0) {
                        $permission->Depth = 0;
                    } else {
                        $parent = Permission::find($parentId);
                        if ($parent) {
                            $permission->Depth = $parent->Depth + 1;
                        } else {
                            $permission->Depth = 0;
                        }
                    }
                    // -- end: depth --

                    $permission->Name = $request->Name;
                    $permission->IsDeleted = 0;
                    $permission->UpdatedDate = now();
                    $permission->UpdatedBy = $user->USE_ID;

                    $permission->save();
                    return BaseResult::withData($permission);
                } catch (\Exception $e) {
                    return BaseResult::error(500, $e->getMessage());
                }
            } else {
                return BaseResult::error(404, 'Data not found!.');
            }
        }
    }
    public function delete($id)
    {
        $permission = Permission::find($id);
        if ($permission) {
            $user = Session::get('user');

            $permission->IsDeleted = 1;
            $permission->UpdatedDate = now();
            $permission->UpdatedBy = $user->USE_ID;
            $permission->save();
            return BaseResult::withData($permission);
        } else {
            return BaseResult::error(404, 'Không tìm thấy dữ liệu!.');
        }
    }
}
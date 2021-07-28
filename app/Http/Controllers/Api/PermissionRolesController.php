<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PermissionRole;
use Illuminate\Http\Request;
use App\Http\Responses\BaseResult;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PermissionRolesController extends Controller
{
    public function get($id = null)
    {
        $data = null;
        if ($id) {
            $data = PermissionRole::where(['IsDeleted' => 0, 'PERO_ID' => $id])->first();
        } else {
            $data = PermissionRole::join('permissions', function ($join) {
                $join->on('permission_roles.PERMISSION_ID', '=', 'permissions.PERMISSION_ID')
                    ->where(['permission_roles.IsDeleted' => 0, 'permissions.isDeleted' => 0]);
            })->join('roles', function ($join) {
                $join->on('permission_roles.ROLE_ID', '=', 'roles.ROLE_ID')
                    ->where(['permission_roles.IsDeleted' => 0, 'roles.isDeleted' => 0]);
            })->get();
        }
        return BaseResult::withData($data);
    }

    public function add(Request $request)
    {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',

        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $perrole = new PermissionRole();
            try {
                $perrole->PERMISSION_ID = $request->input('PERMISSION_ID');
                $perrole->ROLE_ID = $request->input('ROLE_ID');
                $perrole->IsDeleted = 0;
                $perrole->CreatedDate = now();
                $perrole->CreatedBy = $user->USE_ID;

                $perrole->save();
                return BaseResult::withData($perrole);
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


        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $perrole = PermissionRole::find($request->input('id'));
            if ($perrole) {
                try {
                    $perrole->PERMISSION_ID = $request->input('PERMISSION_ID');
                    $perrole->ROLE_ID = $request->input('ROLE_ID');
                    $perrole->IsDeleted = 0;
                    $perrole->UpdatedDate = now();
                    $perrole->UpdatedBy = $user->USE_ID;

                    $perrole->save();
                    return BaseResult::withData($perrole);
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
        $perrole = PermissionRole::find($id);
        if ($perrole) {
            $user = Session::get('user');

            $perrole->IsDeleted = 1;
            $perrole->UpdatedDate = now();
            $perrole->UpdatedBy = $user->USE_ID;
            $perrole->save();
            return BaseResult::withData($perrole);
        } else {
            return BaseResult::error(404, 'Không tìm thấy dữ liệu!.');
        }
    }
}
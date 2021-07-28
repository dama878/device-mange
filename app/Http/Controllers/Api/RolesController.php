<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Responses\BaseResult;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RolesController extends Controller
{

    public function get($id = null)
    {
        $data = null;
        if ($id) {
            $data = Role::where(['IsDeleted' => 0, 'ROLE_ID' => $id])->first();
        } else {
            $data = Role::where('IsDeleted', 0)->get();
        }
        return BaseResult::withData($data);
    }


    public function add(Request $request)
    {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
           
            'RoleName' => [
                'required',
                Rule::unique('roles')
                    ->where('IsDeleted', 0)
            ],
           

        );
        $customerMessage = [
            'unique' => 'The name role already exists',
        ];
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules,$customerMessage);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $role = new Role;

            try {
                $role->RoleName = $request->RoleName;
                $role->Note = $request->Note;


               
                $role->IsDeleted = 0;
                $role->CreatedDate = now();
                $role->CreatedBy = $user->USE_ID;
                $role->save();

                return BaseResult::withData($role);
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
            'RoleName' => [
                'required',
                Rule::unique('roles')->where('IsDeleted', 0)->ignore(Role::find($request->input('id')))
                    
            ],
            

        );
        $customerMessage = [
            'unique' => 'The name role already exists',
        ];
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules,$customerMessage);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $role = Role::find($request->input('id'));
            if ($role) {
                try {
                    $role->RoleName = $request->RoleName;
                    $role->Note = $request->Note;

                          
                    $role->IsDeleted = 0;
                    $role->UpdatedDate = now();
                    $role->UpdatedBy = $user->USE_ID;

                    $role->save();


                    return BaseResult::withData($role);
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
        $role = Role::find($id);
        if ($role) {
            $user = Session::get('user');

            $role->IsDeleted = 1;
            $role->UpdatedDate = now();
            $role->UpdatedBy = $user->USE_ID;
            $role->save();
            return BaseResult::withData($role);
        } else {
            return BaseResult::error(404, 'Không tìm thấy dữ liệu!.');
        }
    }
}

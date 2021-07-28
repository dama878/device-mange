<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Responses\BaseResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{

    public function get($id = null)
    {
        $data = null;
        if ($id) {
            $data = User::where(['IsDeleted' => 0, 'USE_ID' => $id])->first();
        } else {
            $data= User:: join('roles', function ($join) {
                $join->on('users.ROLE_ID', '=', 'roles.ROLE_ID')
                     ->where(['users.IsDeleted'=> 0,'roles.isDeleted'=>0]) ;
                })->get();
        }
        return BaseResult::withData($data);
    }


    public function add(Request $request)
    {
        //validate the info, create rules for the inputs
        $rules = array(
            'password' => 'required|confirmed|min:6',
            
            'id' => 'numeric',
            
            
            'username' => [
                'required',
                Rule::unique('users')
                    ->where('IsDeleted', 0)
            ],
        );
        $customerMessage = [
            'unique' => 'The name user already exists',
        ];
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules,$customerMessage);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $user = new User;

            try {
                $user->ROLE_ID = $request->ROLE_ID;
                $user->username = $request->username;
                $user->FirstName = $request->FirstName;
                $user->LastName = $request->LastName;
                $user->Address = $request->Address;
                $user->Phone = $request->Phone;
                $user->Email = $request->Email;
                $user->Password = bcrypt($request->password) ;
                $user->Gender = $request->Gender;
                $user->Status = $request->Status;
                $user->DOB = $request->DOB;
                $user->Date = $request->Date;


                
                $user->IsDeleted = 0;
                $user->CreatedDate = now();
                $user->CreatedBy = $user->USE_ID;
                $user->save();

                return BaseResult::withData($user);
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
            'password' => 'required|confirmed|min:6',
            
            'username' => [
                'required',
                Rule::unique('users')->where('IsDeleted', 0)->ignore(User::find($request->input('id')))
                    
            ],

        );
        $customerMessage = [
            'unique' => 'The name user already exists',
        ];
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules,$customerMessage);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $user = User::find($request->input('id'));
            if ($user) {
                try {
                    $user->ROLE_ID = $request->ROLE_ID;
                    $user->username = $request->username;
                    $user->FirstName = $request->FirstName;
                    $user->LastName = $request->LastName;
                    $user->Address = $request->Address;
                    $user->Phone = $request->Phone;
                    $user->Email = $request->Email;
                    $user->Password = bcrypt($request->password) ;
                    $user->Gender = $request->Gender;
                    $user->Status = $request->Status;
                    $user->DOB = $request->DOB;
                    $user->Date = $request->Date;
                    
                    $user->IsDeleted = 0;
                    $user->UpdatedDate = now();
                    $user->UpdatedBy = $user->USE_ID;

                    $user->save();


                    return BaseResult::withData($user);
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
        $user = User::find($id);
        if ($user) {
            $user = Session::get('user');

            $user->IsDeleted = 1;
            $user->UpdatedDate = now();
            $user->UpdatedBy = $user->USE_ID;
            $user->save();
            return BaseResult::withData($user);
        } else {
            return BaseResult::error(404, 'Không tìm thấy dữ liệu!.');
        }
    }
}

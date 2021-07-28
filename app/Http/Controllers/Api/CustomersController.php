<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResult;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CustomersController extends Controller
{
    public function get($id = null)
    {
        $data = null;
        if ($id) {
            $data = Customer::where(['IsDeleted' => 0, 'CUS_ID' => $id])->first();
        } else {
            $data = Customer::where('IsDeleted', 0)->get();
        }
        return BaseResult::withData($data);
    }
    public function add(Request $request)
    {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
            'FullName' => 'required',
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $customer = new Customer();
            try {
                $customer->FullName = $request->input('FullName');
                $customer->DayOfBirth = $request->input('DayOfBirth');
                $customer->Phone = $request->input('Phone');
                $customer->Email = $request->input('Email');
                $customer->Address = $request->input('Address');
                $customer->IsDeleted = 0;
                // $export->CreatedBy = $user->USE_ID;

                $customer->save();
                return BaseResult::withData($customer);
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
            'FullName' => 'required',
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $customer = Customer::find($request->input('id'));
            if ($customer) {
                try {
                    $customer->FullName = $request->input('FullName');
                    $customer->DayOfBirth = $request->input('DayOfBirth');
                    $customer->Phone = $request->input('Phone');
                    $customer->Email = $request->input('Email');
                    $customer->Address = $request->input('Address');
                    $customer->IsDeleted = 0;
                    // $export->UpdatedBy = $user->USE_ID;

                    $customer->save();
                    return BaseResult::withData($customer);
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
        $customer = Customer::find($id);
        if ($customer) {
            $user = Session::get('user');

            $customer->IsDeleted = 1;
            // $export->UpdatedBy = $user->USE_ID;
            $customer->save();
            return BaseResult::withData($customer);
        } else {
            return BaseResult::error(404, 'Không tìm thấy dữ liệu!.');
        }
    }
}

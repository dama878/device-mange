<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Responses\BaseResult;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ManufacturersController extends Controller
{

    public function get($id = null)
    {
        $data = null;
        if ($id) {
            $data = Manufacturer::where(['IsDeleted' => 0, 'MAN_ID' => $id])->first();
        } else {
            $data = Manufacturer::where('IsDeleted', 0)->get();
        }
        return BaseResult::withData($data);
    }


    public function add(Request $request)
    {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
            'ManName' => [
                'required',
                Rule::unique('manufacturers')
                    ->where('IsDeleted', 0)
            ],
           
            'DisplayOrder' => 'required|numeric|min:1',
           
        );
        $customerMessage = [
            'unique' => 'The manufacturer already exists',
        ];
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules,$customerMessage);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $manufacturer = new Manufacturer;

            try {
                $manufacturer->ManName = $request->ManName;
                $manufacturer->Address = $request->Address;
                $manufacturer->Note = $request->Note;


                $manufacturer->DisplayOrder = $request->DisplayOrder;
                $manufacturer->IsPublished = $request->has('IsPublished') ? true : false;
                $manufacturer->IsDeleted = 0;
                $manufacturer->CreatedDate = now();
                $manufacturer->CreatedBy = $user->USE_ID;
                $manufacturer->save();

                return BaseResult::withData($manufacturer);
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
            'DisplayOrder' => 'required|numeric|min:1',
            'ManName' => [
                'required',
                Rule::unique('manufacturers')->where('IsDeleted', 0)->ignore(Manufacturer::find($request->input('id')))
                    
            ],
        );
        $customerMessage = [
            'unique' => 'The name manufacturer already exists',
        ];
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules,$customerMessage);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $manufacturer = Manufacturer::find($request->input('id'));
            if ($manufacturer) {
                try {
                    $manufacturer->ManName = $request->ManName;
                    $manufacturer->Address = $request->Address;
                    $manufacturer->Note = $request->Note;

                    $manufacturer->DisplayOrder = $request->DisplayOrder;
                    // $manufacturer->IsPublished = $request->has('IsPublished') ? true : false;            
                    $manufacturer->IsDeleted = 0;
                    $manufacturer->UpdatedDate = now();
                    $manufacturer->UpdatedBy = $user->USE_ID;

                    $manufacturer->save();


                    return BaseResult::withData($manufacturer);
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
        $manufacturer = Manufacturer::find($id);
        if ($manufacturer) {
            $user = Session::get('user');

            $manufacturer->IsDeleted = 1;
            $manufacturer->UpdatedDate = now();
            $manufacturer->UpdatedBy = $user->USE_ID;
            $manufacturer->save();
            return BaseResult::withData($manufacturer);
        } else {
            return BaseResult::error(404, 'Không tìm thấy dữ liệu!.');
        }
    }
}

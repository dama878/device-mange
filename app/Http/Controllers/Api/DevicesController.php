<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResult;
use App\Models\Device;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DevicesController extends Controller
{
    public function get($id = null) {
        $data = null;
        if ($id) {
            $data = Device::where(['IsDeleted' => 0, 'DEV_ID' => $id])->first();
        } 
        else {
                $data= Device:: join('types', function ($join) {
            $join->on('devices.TYPE_ID', '=', 'types.TYPE_ID')
                 ->where(['types.IsDeleted'=> 0,'devices.isDeleted'=>0]) ;
            })->join('manufacturers', function ($join) {
                $join->on('devices.MAN_ID', '=', 'manufacturers.MAN_ID')
                     ->where(['manufacturers.IsDeleted'=> 0,'devices.isDeleted'=>0]) ;
                })->get();

                         
        } 
        return BaseResult::withData($data);
    }
    public function add(Request $request) {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
            'DevName' => [
                'required',
                Rule::unique('devices')
                    ->where('IsDeleted', 0)
            ],
            'DisplayOrder' => 'required|numeric|min:1',
            'Img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10000'
        );
        $customerMessage = [
            'unique' => 'The devices already exists',
        ];
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules, $customerMessage);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $device = new Device;
           
            try {
                // $dates=explode('-',$request->GuaranteeDate);
                // $device->GuaranteeStart = $dates[0];
                // $device->GuaranteeEnd =$dates[1];
                $device->DevName = $request->DevName;
                $device->TYPE_ID = $request->TYPE_ID;
                $device->MAN_ID = $request->MAN_ID;
                $device->Description = $request->Description;
                $device->KeyWord = $request->KeyWord;
                $device->Status = $request->Status;
                $device->SerialNumber = $request->SerialNumber;
                $device->Detail = $request->Detail;
               
                $device->GuaranteeStart = $request->GuaranteeStart;
                $device->GuaranteeEnd = $request->GuaranteeEnd;
                
                $device->DisplayOrder = $request->DisplayOrder;  
                $device->IsPublished = $request->has('IsPublished') ? true : false; 
                $device->IsDeleted = 0;
                $device->CreatedDate = now();
                $device->CreatedBy = $user->USE_ID;
                $device->save();

                if ($request->hasFile('Img')) {
                    $filename = pathinfo($request->Img->getClientOriginalName(), PATHINFO_FILENAME);
                    $imageName = $device->DEV_ID . '_' . $filename . '_' . time() . '.' . $request->Img->extension();
                    $request->Img->move(public_path('data/devices'), $imageName);
                    $device->Img = $imageName;
                    $device->save();
                }

                return BaseResult::withData($device);
            } catch (\Exception $e) {
                return BaseResult::error(500, $e->getMessage());
            }
        }
    }
    public function update(Request $request) {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
            'DevName' => [
                'required',
                Rule::unique('devices')->where('IsDeleted', 0)->ignore(Device::find($request->input('id')))
                    
            ],
            'DisplayOrder' => 'required|numeric|min:1',
            'Img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10000'
        );
        $customerMessage = [
            'unique' => 'The devices already exists',
        ];
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules, $customerMessage);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $device = Device::find($request->input('id'));
            if ($device) {
                try {
                    $device->DevName = $request->DevName;
                    $device->TYPE_ID = $request->TYPE_ID;
                    $device->MAN_ID = $request->MAN_ID;
                    $device->Description = $request->Description;
                    $device->KeyWord = $request->KeyWord;
                    $device->Status = $request->Status;
                    $device->SerialNumber = $request->SerialNumber;
                    $device->Detail = $request->Detail;
                    $device->GuaranteeStart = $request->GuaranteeStart;
                    $device->GuaranteeEnd = $request->GuaranteeEnd;
                    
                    $device->DisplayOrder = $request->DisplayOrder;                
                    $device->IsDeleted = 0;
                    $device->UpdatedDate = now();
                    $device->UpdatedBy = $user->USE_ID;

                    $device->update();

                    if ($request->hasFile('Img')) {
                        
                        // delete old file
                        $oldFile = $device->Img;
                        if (File::exists(public_path('data/devices/' . $oldFile))) {
                            File::delete(public_path('data/devices/' . $oldFile));
                        }

                        $filename = pathinfo($request->Img->getClientOriginalName(), PATHINFO_FILENAME);
                        $imageName = $device->DEV_ID . '_' . $filename . '_' . time() . '.' . $request->Img->extension();
                        $request->Img->move(public_path('data/devices'), $imageName);
                        $device->Img = $imageName;
                        $device->save();
                    }

                    return BaseResult::withData($device);
                } catch (\Exception $e) {
                    return BaseResult::error(500, $e->getMessage());
                }
            } else {
                return BaseResult::error(404, 'Data not found!.');
            }
        }
    }
    public function delete($id) {
        $device = Device::find($id);
        if ($device) {
            $user = Session::get('user');

            $device->IsDeleted = 1;
            $device->UpdatedDate = now();
            $device->UpdatedBy = $user->USE_ID;
            $device->save();
            return BaseResult::withData($device);
        } else {
            return BaseResult::error(404, 'Không tìm thấy dữ liệu!.');
        }
    }
}

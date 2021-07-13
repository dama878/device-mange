<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResult;
use App\Models\Device;
use App\Models\Type;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
            })->get();
        } 
        return BaseResult::withData($data);
    }
    public function add(Request $request) {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
            'Name' => 'required',
            'DisplayOrder' => 'required|numeric|min:1',
            'Image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $device = new Device;
           
            try {
                $device->Name = $request->Name;
                $device->TYPE_ID = $request->TYPE_ID;
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

                if ($request->hasFile('Image')) {
                    $filename = pathinfo($request->Image->getClientOriginalName(), PATHINFO_FILENAME);
                    $imageName = $device->DEV_ID . '_' . $filename . '_' . time() . '.' . $request->Image->extension();
                    $request->Image->move(public_path('data/devices'), $imageName);
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
            'Name' => 'required',
            'DisplayOrder' => 'required|numeric|min:1',
            'Image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $device = Device::find($request->input('id'));
            if ($device) {
                try {
                    $device->Name = $request->Name;
                    $device->TYPE_ID = $request->TYPE_ID;
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

                    $device->save();

                    if ($request->hasFile('Image')) {
                        
                        // delete old file
                        $oldFile = $device->Img;
                        if (File::exists(public_path('data/devices/' . $oldFile))) {
                            File::delete(public_path('data/devices/' . $oldFile));
                        }

                        $filename = pathinfo($request->Image->getClientOriginalName(), PATHINFO_FILENAME);
                        $imageName = $device->DEV_ID . '_' . $filename . '_' . time() . '.' . $request->Image->extension();
                        $request->Image->move(public_path('data/devices'), $imageName);
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

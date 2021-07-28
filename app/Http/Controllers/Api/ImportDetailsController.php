<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResult;
use App\Models\ImportDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ImportDetailsController extends Controller
{
    public function get($id = null)
    {
        $data = null;
        if ($id) {
            $data = ImportDetail::where(['IsDeleted' => 0, 'IMPDE_ID' => $id])->first();
        } else {
            $data = ImportDetail::join('imports', function ($join) {
                $join->on('imports.IMP_ID', '=', 'import_details.IMP_ID')
                    ->where(['imports.IsDeleted' => 0, 'import_details.isDeleted' => 0]);
            })->get();
        }
        return BaseResult::withData($data);
    }

    public function add(Request $request)
    {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
            'IMP_ID' => 'required',
            'Price' => 'required|numeric|min:10',
            'Quantity' => 'required|numeric|min:3'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $importDetail = new ImportDetail();
            try {
                $importDetail->IMP_ID = $request->IMP_ID;
                $importDetail->MOD_ID = $request->MOD_ID;
                $importDetail->Unit = $request->Unit;
                $importDetail->Type = $request->Type;
                $importDetail->Quantity = $request->Quantity;
                $importDetail->Price = $request->Price;
                $importDetail->Note = $request->input('Note');
                $importDetail->IsDeleted = 0;
                // $importDetail->CreatedBy = $user->USE_ID;

                $importDetail->save();
                return BaseResult::withData($importDetail);
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
            'IMP_ID' => 'required',
            'Price' => 'required|numeric|min:10',
            'Quantity' => 'required|numeric|min:3'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $importDetail = ImportDetail::find($request->input('id'));
            if ($importDetail) {
                try {
                    $importDetail->IMP_ID = $request->IMP_ID;

                    $importDetail->MOD_ID = $request->MOD_ID;
                    $importDetail->Unit = $request->Unit;
                    $importDetail->Type = $request->Type;
                    $importDetail->Quantity = $request->Quantity;
                    $importDetail->Price = $request->Price;
                    $importDetail->Note = $request->input('Note');
                    $importDetail->IsDeleted = 0;
                    // $importDetail->CreatedBy = $user->USE_ID;

                    $importDetail->save();
                    return BaseResult::withData($importDetail);
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
        $importDetail = ImportDetail::find($id);
        if ($importDetail) {
            $user = Session::get('user');

            $importDetail->IsDeleted = 1;
            // $importDetail->UpdatedBy = $user->USE_ID;
            $importDetail->save();
            return BaseResult::withData($importDetail);
        } else {
            return BaseResult::error(404, 'Data not found!.');
        }
    }
}

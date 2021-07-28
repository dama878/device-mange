<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResult;
use App\Models\ExportDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ExportDetailsController extends Controller
{
    public function get($id = null)
    {
        $data = null;
        if ($id) {
            $data = ExportDetail::where(['IsDeleted' => 0, 'EXPDE_ID' => $id])->first();
        } else {
            $data = ExportDetail::join('exports', function ($join) {
                $join->on('exports.EXP_ID', '=', 'export_details.EXP_ID')
                    ->where(['exports.IsDeleted' => 0, 'export_details.isDeleted' => 0]);
            })->get();
        }
        return BaseResult::withData($data);
    }

    public function add(Request $request)
    {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
            'EXP_ID' => 'required',
            'Price' => 'required|numeric|min:10',
            'Quantity' => 'required|numeric|min:3'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $exportDetail = new ExportDetail();
            try {
                $exportDetail->EXP_ID = $request->EXP_ID;
                $exportDetail->MOD_ID = $request->MOD_ID;
                $exportDetail->Unit = $request->Unit;
                $exportDetail->Type = $request->Type;
                $exportDetail->Quantity = $request->Quantity;
                $exportDetail->Price = $request->Price;
                $exportDetail->Note = $request->input('Note');
                $exportDetail->IsDeleted = 0;
                // $exportDetail->CreatedBy = $user->USE_ID;

                $exportDetail->save();
                return BaseResult::withData($exportDetail);
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
            'EXP_ID' => 'required',
            'Price' => 'required|numeric|min:10',
            'Quantity' => 'required|numeric|min:3'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $exportDetail = ExportDetail::find($request->input('id'));
            if ($exportDetail) {
                try {
                    $exportDetail->EXP_ID = $request->EXP_ID;
                    $exportDetail->MOD_ID = $request->MOD_ID;
                    $exportDetail->Unit = $request->Unit;
                    $exportDetail->Type = $request->Type;
                    $exportDetail->Quantity = $request->Quantity;
                    $exportDetail->Price = $request->Price;
                    $exportDetail->Note = $request->input('Note');
                    $exportDetail->IsDeleted = 0;
                    // $exportDetail->CreatedBy = $user->USE_ID;

                    $exportDetail->save();
                    return BaseResult::withData($exportDetail);
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
        $exportDetail = ExportDetail::find($id);
        if ($exportDetail) {
            $user = Session::get('user');

            $exportDetail->IsDeleted = 1;
            // $exportDetail->UpdatedBy = $user->USE_ID;
            $exportDetail->save();
            return BaseResult::withData($exportDetail);
        } else {
            return BaseResult::error(404, 'Data not found!.');
        }
    }
}

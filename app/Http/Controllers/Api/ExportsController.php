<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResult;
use App\Models\Export;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ExportsController extends Controller
{
    public function get($id = null)
    {
        $data = null;
        if ($id) {
            $data = Export::where(['IsDeleted' => 0, 'EXP_ID' => $id])->first();
        } else {
            $data = Export::join('customers', function ($join) {
                $join->on('exports.CUS_ID', '=', 'customers.CUS_ID')
                    ->where(['exports.IsDeleted' => 0, 'customers.isDeleted' => 0]);
            })->get();
        }
        return BaseResult::withData($data);
    }
    public function add(Request $request)
    {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
            'CUS_ID' => 'required',
            'Date' => 'required',
            'Export' => 'numeric|min:1',
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $export = new Export();
            try {
                $export->Invoice = $request->input('Invoice');
                $export->Date = $request->input('Date');
                $export->CUS_ID = $request->input('CUS_ID');
                $export->Depot = $request->input('Depot');
                $export->Place = $request->input('Place');
                $export->Export = $request->input('Export');
                $export->IsDeleted = 0;
                $export->CreatedBy = $user->USE_ID;

                $export->save();
                return BaseResult::withData($export);
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
            'CUS_ID' => 'required',
            'Date' => 'required',
            'Export' => 'numeric|min:1',
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $export = Export::find($request->input('id'));
            if ($export) {
                try {
                    $export->Invoice = $request->input('Invoice');
                    $export->Date = $request->input('Date');
                    $export->CUS_ID = $request->input('CUS_ID');
                    $export->Depot = $request->input('Depot');
                    $export->Place = $request->input('Place');
                    $export->Export = $request->input('Export');
                    $export->IsDeleted = 0;
                    $export->UpdatedBy = $user->USE_ID;

                    $export->save();
                    return BaseResult::withData($export);
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
        $export = Export::find($id);
        if ($export) {
            $user = Session::get('user');

            $export->IsDeleted = 1;
            $export->UpdatedBy = $user->USE_ID;
            $export->save();
            return BaseResult::withData($export);
        } else {
            return BaseResult::error(404, 'Không tìm thấy dữ liệu!.');
        }
    }
}

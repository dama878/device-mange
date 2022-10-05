<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResult;
use App\Models\Import;
use App\Models\ImportDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ImportsController extends Controller
{
    public function get($id = null)
    {
        $data = null;
        if ($id) {
            $data = Import::where(['IsDeleted' => 0, 'IMP_ID' => $id])->first();
        } else {
            $data = Import::join('customers', function ($join) {
                $join->on('imports.CUS_ID', '=', 'customers.CUS_ID')
                    ->where(['imports.IsDeleted' => 0, 'customers.isDeleted' => 0]);
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
            'Import' => 'numeric|min:1',
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $import = new Import();
            $importDetail = new ImportDetail();
            try {
                $import->Invoice = $request->input('Invoice');
                $import->Date = $request->input('Date');
                $import->CUS_ID = $request->input('CUS_ID');
                $import->Depot = $request->input('Depot');
                $import->Place = $request->input('Place');
                $import->Import = $request->input('Import');
                $import->IsDeleted = 0;
                $import->CreatedBy = $user->USE_ID;
                $import->save();

                $importDetail->IMP_ID = $import->IMP_ID;
                $importDetail->MOD_ID = $request->MOD_ID;
                $importDetail->Unit = $request->Unit;
                $importDetail->Type = $request->Type;
                $importDetail->Quantity = $request->Quantity;
                $importDetail->Price = $request->Price;
                $importDetail->Note = $request->input('Note');
                $importDetail->IsDeleted = 0;
                $importDetail->CreatedBy = $user->USE_ID;
                $importDetail->save();

                return BaseResult::withData($import);
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
            'Import' => 'numeric|min:1',
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $import = Import::find($request->input('id'));
            if ($import) {
                try {
                    $import->Invoice = $request->input('Invoice');
                    $import->Date = $request->input('Date');
                    $import->CUS_ID = $request->input('CUS_ID');
                    $import->Depot = $request->input('Depot');
                    $import->Place = $request->input('Place');
                    $import->Import = $request->input('Import');
                    $import->IsDeleted = 0;
                    $import->UpdatedBy = $user->USE_ID;

                    $import->save();
                    return BaseResult::withData($import);
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
        $import = Import::find($id);
        if ($import) {
            $user = Session::get('user');

            $import->IsDeleted = 1;
            $import->UpdatedBy = $user->USE_ID;
            $import->save();
            return BaseResult::withData($import);
        } else {
            return BaseResult::error(404, 'Không tìm thấy dữ liệu!.');
        }
    }
}

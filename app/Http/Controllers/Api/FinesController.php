<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResult;
use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class FinesController extends Controller
{
    public function get($id = null)
    {
        $data = null;
        if ($id) {
            $data = Fine::where(['IsDeleted' => 0, 'FINE_ID' => $id])->first();
        } else {
            $data = Fine::join('borrowers', function ($join) {
                $join->on('fines.BORROWER_ID', '=', 'borrowers.BORROWER_ID')
                    ->where(['fines.IsDeleted' => 0, 'borrowers.isDeleted' => 0]);
            })->get();
        }

        return BaseResult::withData($data);
    }

    public function add(Request $request)
    {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
            'BORROWER_ID' => 'required',
            'Money' => 'required',
            'Reason' => 'required',
            'Date' => 'required'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $fine = new Fine();
            try {
                $fine->BORROWER_ID = $request->input('BORROWER_ID');
                $fine->Money = $request->input('Money');
                $fine->Reason = $request->input('Reason');
                $fine->Date = $request->input('Date');
                $fine->IsDeleted = 0;
                $fine->CreatedDate = now();
                $fine->CreatedBy = $user->USE_ID;

                $fine->save();
                return BaseResult::withData($fine);
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
            'BORROWER_ID' => 'required',
            'Money' => 'required',
            'Reason' => 'required',
            'Date' => 'required'

        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $fine = Fine::find($request->input('id'));
            if ($fine) {
                try {
                    $fine->BORROWER_ID = $request->input('BORROWER_ID');
                    $fine->Money = $request->input('Money');
                    $fine->Reason = $request->input('Reason');
                    $fine->Date = $request->input('Date');
                    $fine->IsDeleted = 0;
                    $fine->UpdatedDate = now();
                    $fine->UpdatedBy = $user->USE_ID;

                    $fine->save();
                    return BaseResult::withData($fine);
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
        $fine = Fine::find($id);
        if ($fine) {
            $user = Session::get('user');

            $fine->IsDeleted = 1;
            $fine->UpdatedDate = now();
            $fine->UpdatedBy = $user->USE_ID;
            $fine->save();
            return BaseResult::withData($fine);
        } else {
            return BaseResult::error(404, 'Không tìm thấy dữ liệu!.');
        }
    }
}
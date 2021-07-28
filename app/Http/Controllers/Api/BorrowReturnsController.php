<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResult;
use App\Models\BorrowerReturn;
use App\Models\BorrowReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BorrowReturnsController extends Controller
{
    public function get($id = null)
    {
        $data = null;
        if ($id) {
            $data = BorrowReturn::where(['IsDeleted' => 0, 'BORETURN_ID' => $id])->first();
        } else {
            $data = BorrowReturn::join('borrowers', function ($join) {
                $join->on('borrow_returns.BORROWER_ID', '=', 'borrowers.BORROWER_ID')
                    ->where(['borrow_returns.IsDeleted' => 0, 'borrowers.isDeleted' => 0]);
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
            'Date' => 'required'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $borrowerreturn = new BorrowReturn();
            try {
                $borrowerreturn->BORROWER_ID = $request->input('BORROWER_ID');
                $borrowerreturn->Date = $request->input('Date');
                $borrowerreturn->IsDeleted = 0;
                $borrowerreturn->CreatedDate = now();
                $borrowerreturn->CreatedBy = $user->USE_ID;

                $borrowerreturn->save();
                return BaseResult::withData($borrowerreturn);
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
            'Date' => 'required'

        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $borrowerreturn = BorrowReturn::find($request->input('id'));
            if ($borrowerreturn) {
                try {
                    $borrowerreturn->BORROWER_ID = $request->input('BORROWER_ID');
                    $borrowerreturn->Date = $request->input('Date');
                    $borrowerreturn->IsDeleted = 0;
                    $borrowerreturn->UpdatedDate = now();
                    $borrowerreturn->UpdatedBy = $user->USE_ID;

                    $borrowerreturn->save();
                    return BaseResult::withData($borrowerreturn);
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
        $borrowerreturn = BorrowReturn::find($id);
        if ($borrowerreturn) {
            $user = Session::get('user');

            $borrowerreturn->IsDeleted = 1;
            $borrowerreturn->UpdatedDate = now();
            $borrowerreturn->UpdatedBy = $user->USE_ID;
            $borrowerreturn->save();
            return BaseResult::withData($borrowerreturn);
        } else {
            return BaseResult::error(404, 'Không tìm thấy dữ liệu!.');
        }
    }
}
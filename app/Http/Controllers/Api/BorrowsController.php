<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResult;
use Illuminate\Http\Request;
use App\Models\Borrow;
use App\Models\BorrowDetailModel;
use Illuminate\Support\Facades\Validator;   

class BorrowsController extends Controller
{
    public function get($id = null){
        $data = null;
        if($id){
            $data = Borrow::where(['IsDeleted' => 0, 'BOR_ID' => $id])->first();
        }else{
            $data= Borrow:: join('borrowers', function ($join) {
                $join->on('borrows.BORROWER_ID', '=', 'borrowers.BORROWER_ID')
                     ->where(['borrows.IsDeleted'=> 0,'borrowers.isDeleted'=>0]) ;
                })->get();
        }
        return BaseResult::withData($data);
    }
//Add
    public function add(Request $request)
    {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
            'BORROWER_ID' => 'required',
            'Date' => 'required',
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
           //  $user = Session::get('user');
            $borrow = new Borrow();
            $borrowdetaildetail = new BorrowDetailModel();
            try {
                $borrow->BORROWER_ID = $request->BORROWER_ID;
                $borrow->Date = $request->Date;
                $borrow->IsDeleted = 0;
                $borrow->CreatedDate = now();
              //  $borrow->CreatedBy = $user->USE_ID;
                $borrow->save();
                    $borrowdetaildetail->MOD_ID = 4;
                    $borrowdetaildetail->BOR_ID = $borrow->BOR_ID;
                    $borrowdetaildetail->BORETURN_ID = 1;
                    $borrowdetaildetail->DueDateReturn = $request->DueDateReturn;
                    $borrowdetaildetail->DateReturn = $request->DateReturn;
                    $borrowdetaildetail->IsRenew = $request->has('IsRenew') ? true : false; 
                    $borrowdetaildetail->IsDeleted = 0;
                    $borrowdetaildetail->CreatedDate = now();
                    // $category->CreatedBy = $user->USE_ID;

                    $borrowdetaildetail->save();
                // if($request->MOD_ID!=null){
                //     $modelIds=array_values($request->MOD_ID);
                //     $borrow->models()->sync($modelIds);
                // }
                return BaseResult::withData($borrow);
            } catch (\Exception $e) {
                return BaseResult::error(500, $e->getMessage());
            }
        }
    }
// Update
        public function update(Request $request) {
            //validate the info, create rules for the inputs
            $rules = array(
                'id' => 'numeric',
                'BORROWER_ID' => 'required',
                'Date' => 'required',
            );
            // run the validation rules on the inputs from the form
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return BaseResult::error(400, $validator->messages()->toJson());
            } else {
              //   $user = Session::get('user');
                $borrow = Borrow::find($request->input('id'));
                if ($borrow) {
                    try {
                        $borrow->BORROWER_ID = $request->BORROWER_ID;
                        $borrow->Date = $request->input('Date');
                        $borrow->IsDeleted = 0;
                        $borrow->UpdatedDate = now();
                      //  $borrow->UpdatedBy = $user->USE_ID;

                        $borrow->save();
                        return BaseResult::withData($borrow);
                    } catch (\Exception $e) {
                        return BaseResult::error(500, $e->getMessage());
                    }
                } else {
                    return BaseResult::error(404, 'Data not found!.');
                }
            }
        }
    //Delete
    public function delete($id) {
        $borrow = Borrow::find($id);
        if ($borrow) {
            //$user = Session::get('user');

            $borrow->IsDeleted = 1;
            $borrow->UpdatedDate = now();
          //  $borrow->UpdatedBy = $user->USE_ID;
            $borrow->save();
            return BaseResult::withData($borrow);
        } else {
            return BaseResult::error(404, 'Không tìm thấy dữ liệu!.');
        }
    }
}
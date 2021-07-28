<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResult;
use Illuminate\Http\Request;
use App\Models\BorrowDetailModel;
use Illuminate\Support\Facades\Validator;   

class BorrowDetailsModelController extends Controller{
    public function get($id = null){
        $data = null;
        if($id){
            $data = BorrowDetailModel::where(['IsDeleted' => 0, 'BORDE_ID' => $id])->first();
        }else{
            $data= BorrowDetailModel::join('models', function ($join) {
                $join->on('borrow_detail_models.MOD_ID', '=', 'models.MOD_ID')
                     ->where(['borrow_detail_models.IsDeleted'=> 0,'models.isDeleted'=>0]) ;
                })->join('borrows', function ($join) {
                    $join->on('borrow_detail_models.BOR_ID', '=', 'borrows.BOR_ID')
                         ->where(['borrow_detail_models.IsDeleted'=> 0,'borrows.isDeleted'=>0]) ;
                    })->join('borrowers', function ($join) {
                        $join->on('borrowers.BORROWER_ID', '=', 'borrows.BORROWER_ID')
                            ->where(['borrowers.IsDeleted' => 0, 'borrows.IsDeleted' => 0]);
                    })->get();
            // backup
            // $data= BorrowDetailModel::join('models', function ($join) {
            //     $join->on('borrow_detail_models.MOD_ID', '=', 'models.MOD_ID')
            //          ->where(['borrow_detail_models.IsDeleted'=> 0,'models.isDeleted'=>0]) ;
            //     })->join('borrows', function ($join) {
            //         $join->on('borrow_detail_models.BOR_ID', '=', 'borrows.BOR_ID')
            //              ->where(['borrow_detail_models.IsDeleted'=> 0,'borrows.isDeleted'=>0]) ;
            //         })->get();
        }
        return BaseResult::withData($data);
    }

    public function getByID($id){
        $data= BorrowDetailModel::join('models', function ($join) {
            $join->on('borrow_detail_models.MOD_ID', '=', 'models.MOD_ID')
                 ->where(['borrow_detail_models.IsDeleted'=> 0,'models.isDeleted'=>0]) ;
            })->join('borrows', function ($join) use ($id) {
                $join->on('borrow_detail_models.BOR_ID', '=', 'borrows.BOR_ID')
                        ->where(['borrow_detail_models.IsDeleted'=> 0,
                                    'borrows.isDeleted'=>0, 
                                    'borrow_detail_models.BOR_ID' => $id]) ;
                })->get();
        return BaseResult::withData($data);
    }

    //Add
    public function add(Request $request)
    {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
            'MOD_ID' => 'required',
            'BOR_ID' => 'required',
            'BORETURN_ID' => 'required',
            'DueDateReturn' => 'required',
            'DateReturn' => 'required',
            // 'IsRenew' => 'required'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            // $user = Session::get('user');
            $borrowdetaildetail = new BorrowDetailModel();
            try {
                $borrowdetaildetail->MOD_ID = $request->MOD_ID;
                $borrowdetaildetail->BOR_ID = $request->BOR_ID;
                $borrowdetaildetail->BORETURN_ID = $request->BORETURN_ID;
                $borrowdetaildetail->DueDateReturn = $request->DueDateReturn;
                $borrowdetaildetail->DateReturn = $request->DateReturn;
                $borrowdetaildetail->IsRenew = $request->has('IsRenew') ? true : false; 
                $borrowdetaildetail->IsDeleted = 0;
                $borrowdetaildetail->CreatedDate = now();
                // $category->CreatedBy = $user->USE_ID;

                $borrowdetaildetail->save();
                return BaseResult::withData($borrowdetaildetail);
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
            'MOD_ID' => 'required',
            'BOR_ID' => 'required',
            'BORETURN_ID' => 'required',
            'DueDateReturn' => 'required',
            'DateReturn' => 'required',
            //'IsRenew' => 'required'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            // $user = Session::get('user');
            $borrowdetaildetail = BorrowDetailModel::find($request->input('id'));
            if ($borrowdetaildetail) {
                try {
                    $borrowdetaildetail->MOD_ID = $request->MOD_ID;
                    $borrowdetaildetail->BOR_ID = $request->BOR_ID;
                    $borrowdetaildetail->BORETURN_ID = $request->BORETURN_ID;
                    $borrowdetaildetail->DueDateReturn = $request->DueDateReturn;
                    $borrowdetaildetail->DateReturn = $request->DateReturn;
                    $borrowdetaildetail->IsRenew = $request->has('IsRenew') ? true : false; 
                    $borrowdetaildetail->IsDeleted = 0;
                    $borrowdetaildetail->CreatedDate = now();
                    // $borrowdetail->UpdatedBy = $user->USE_ID;

                    $borrowdetaildetail->save();
                    return BaseResult::withData($borrowdetaildetail);
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
        $borrowdetaildetail = BorrowDetailModel::find($id);
        if ($borrowdetaildetail) {
            // $user = Session::get('user');

            $borrowdetaildetail->IsDeleted = 1;
            $borrowdetaildetail->UpdatedDate = now();
            // $borrowdetaildetail->UpdatedBy = $user->USE_ID;
            $borrowdetaildetail->save();
            return BaseResult::withData($borrowdetaildetail);
        } else {
            return BaseResult::error(404, 'Không tìm thấy dữ liệu!.');
        }
    }

}
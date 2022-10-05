<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResult;
use App\Models\Borrower;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BorrowersController extends Controller
{
    public function get($id = null)
    {
        $data = null;
        if ($id) {
            $data = Borrower::where(['IsDeleted' => 0, 'BORROWER_ID' => $id])->first();
        } else {
            $data = Borrower::join('borrower_groups', function ($join) {
                $join->on('borrowers.BOGROUP_ID', '=', 'borrower_groups.BOGROUP_ID')
                    ->where(['borrowers.IsDeleted' => 0, 'borrower_groups.isDeleted' => 0]);
            })->get();
        }
        return BaseResult::withData($data);
    }

    public function add(Request $request)
    {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
            'BOGROUP_ID' => 'required',
            'FirstName' => 'required',
            'LastName' => 'required',
            'Phone' => 'required',
            'Email' => 'required',
            'BorrowerID' => 'required'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $borrower = new Borrower();
            try {
                $borrower->BOGROUP_ID = $request->input('BOGROUP_ID');
                $borrower->BorrowerID = $request->input('BorrowerID');
                $borrower->FirstName = $request->input('FirstName');
                $borrower->LastName = $request->input('LastName');
                $borrower->Phone = $request->input('Phone');
                $borrower->Email = $request->input('Email');
                $borrower->Note = $request->input('Note');
                $borrower->IsDeleted = 0;
                $borrower->CreatedDate = now();
                $borrower->CreatedBy = $user->USE_ID;

                if ($request->hasFile('Image')) {
                    $filename = pathinfo($request->Image->getClientOriginalName(), PATHINFO_FILENAME);
                    $imageName = $borrower->BORROWER_ID . '_' . $filename . '_' . time() . '.' . $request->Image->extension();
                    $request->Image->move(public_path('data/banners'), $imageName);
                    $borrower->Image = $imageName;
                    $borrower->save();
                }

                $borrower->save();
                return BaseResult::withData($borrower);
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
            'BOGROUP_ID' => 'required',
            'FirstName' => 'required',
            'LastName' => 'required',
            'Phone' => 'required',
            'Email' => 'required',
            'BorrowerID' => 'required'

        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $borrower = Borrower::find($request->input('id'));
            if ($borrower) {
                try {
                    $borrower->BOGROUP_ID = $request->input('BOGROUP_ID');
                    $borrower->BorrowerID = $request->input('BorrowerID');
                    $borrower->FirstName = $request->input('FirstName');
                    $borrower->LastName = $request->input('LastName');
                    $borrower->Phone = $request->input('Phone');
                    $borrower->Email = $request->input('Email');
                    $borrower->Note = $request->input('Note');
                    $borrower->IsDeleted = 0;
                    $borrower->UpdatedDate = now();
                    $borrower->UpdatedBy = $user->USE_ID;

                    $borrower->save();

                    if ($request->hasFile('Image')) {

                        // delete old file
                        $oldFile = $borrower->Image;
                        if (File::exists(public_path('data/banners/' . $oldFile))) {
                            File::delete(public_path('data/banners/' . $oldFile));
                        }

                        $filename = pathinfo($request->Image->getClientOriginalName(), PATHINFO_FILENAME);
                        $imageName = $borrower->BORROWER_ID . '_' . $filename . '_' . time() . '.' . $request->Image->extension();
                        $request->Image->move(public_path('data/banners'), $imageName);
                        $borrower->Image = $imageName;
                        $borrower->save();
                    }

                    return BaseResult::withData($borrower);
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
        $borrower = Borrower::find($id);
        if ($borrower) {
            $user = Session::get('user');

            $borrower->IsDeleted = 1;
            $borrower->UpdatedDate = now();
            $borrower->UpdatedBy = $user->USE_ID;
            $borrower->save();
            return BaseResult::withData($borrower);
        } else {
            return BaseResult::error(404, 'Không tìm thấy dữ liệu!.');
        }
    }
    public function exist(Request $request)
    {
        $exist = Borrower::where(['IsDeleted' => 0, 'BorrowerID' => $request->get('BorrowerID')])
            ->where('BorrowerID', '<>', $request->get('id'))->get();
        if ($exist->count() > 0) return 'false';
        return 'true';
    }
}
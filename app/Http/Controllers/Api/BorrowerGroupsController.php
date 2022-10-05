<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResult;
use App\Models\BorrowerGroup;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BorrowerGroupsController extends Controller
{
    public function get($id = null)
    {
        $data = null;
        if ($id) {
            $data = BorrowerGroup::where(['IsDeleted' => 0, 'BOGROUP_ID' => $id])->first();
        } else {
            $data = BorrowerGroup::where('IsDeleted', 0)->get();
        }
        return BaseResult::withData($data);
    }

    public function add(Request $request)
    {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
            // 'Name' => 'required'
            'Name' => [
                'required',
                Rule::unique('borrower_groups')->where('IsDeleted', 0)
            ],
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $borrowergroup = new BorrowerGroup();
            try {
                $borrowergroup->Name = $request->input('Name');
                $borrowergroup->IsDeleted = 0;
                $borrowergroup->CreatedDate = now();
                $borrowergroup->CreatedBy = $user->USE_ID;

                $borrowergroup->save();
                return BaseResult::withData($borrowergroup);
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
            'Name' => 'required'
            // 'Name' => [
            //     'required',
            //     Rule::unique('borrower_groups')->where('IsDeleted', 0)
            //         ->ignore(BorrowerGroup::find($request->input('id')))
            // ],
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $borrowergroup = BorrowerGroup::find($request->input('id'));
            if ($borrowergroup) {
                try {
                    $borrowergroup->Name = $request->input('Name');
                    $borrowergroup->IsDeleted = 0;
                    $borrowergroup->UpdatedDate = now();
                    $borrowergroup->UpdatedBy = $user->USE_ID;

                    $borrowergroup->save();
                    return BaseResult::withData($borrowergroup);
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
        $borrowergroup = BorrowerGroup::find($id);
        if ($borrowergroup) {
            $user = Session::get('user');

            $borrowergroup->IsDeleted = 1;
            $borrowergroup->UpdatedDate = now();
            $borrowergroup->UpdatedBy = $user->USE_ID;
            $borrowergroup->save();
            return BaseResult::withData($borrowergroup);
        } else {
            return BaseResult::error(404, 'Không tìm thấy dữ liệu!.');
        }
    }
    public function exist(Request $request)
    {
        $exist = BorrowerGroup::where(['IsDeleted' => 0, 'Name' => $request->get('Name')])
            ->where('BOGROUP_ID', '<>', $request->get('id'))->get();
        if ($exist->count() > 0) return 'false';
        return 'true';
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResult;
use Illuminate\Http\Request;
use App\Models\Modell;
use Illuminate\Support\Facades\Validator;   

class ModelsController extends Controller
{
    public function get($id = null){
        $data = null;
        if($id){
            $data = Modell::where(['IsDeleted' => 0, 'MOD_ID' => $id])->first();
        }else{
            $data= Modell:: join('devices', function ($join) {
                $join->on('models.DEV_ID', '=', 'devices.DEV_ID')
                     ->where(['models.IsDeleted'=> 0,'devices.isDeleted'=>0]) ;
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
            'DEV_ID' => 'required',
            'NameModel' => 'required',
            'Amount' => 'required|numeric|min:1'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
          //   $user = Session::get('user');
            $model = new Modell();
            try {
                $model->DEV_ID = $request->DEV_ID;
                $model->NameModel = $request->input('NameModel');
                $model->Amount = $request->input('Amount');
                $model->IsDeleted = 0;
                $model->CreatedDate = now();
             //    $model->CreatedBy = $user->USE_ID;-
                $model->save();
                if($request->BOR_ID!=null){
                    $borrowIds=array_values($request->BOR_ID);
                    $model->borrows()->sync($borrowIds);
                }
                return BaseResult::withData($model);
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
            'DEV_ID' => 'required',
            'NameModel' => 'required',
            'Amount' => 'required|numeric|min:1'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
          //  $user = Session::get('user');
            $model = Modell::find($request->input('id'));
            if ($model) {
                try {
                    $model->DEV_ID = $request->DEV_ID;
                    $model->NameModel = $request->input('NameModel');
                    $model->Amount = $request->input('Amount');                
                    $model->IsDeleted = 0;
                    $model->UpdatedDate = now();
                 //   $model->UpdatedBy = $user->USE_ID;

                    $model->save();
                    return BaseResult::withData($model);
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
        $model = Modell::find($id);
        if ($model) {
          //  $user = Session::get('user');

            $model->IsDeleted = 1;
            $model->UpdatedDate = now();
           // $model->UpdatedBy = $user->USE_ID;
            $model->save();
            return BaseResult::withData($model);
        } else {
            return BaseResult::error(404, 'Không tìm thấy dữ liệu!.');
        }
    }
}
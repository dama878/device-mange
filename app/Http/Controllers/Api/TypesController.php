<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResult;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TypesController extends Controller
{
    public function get($id = null) {
        $data = null;
        if ($id) {
            $data = Type::where(['IsDeleted' => 0, 'TYPE_ID' => $id])->first();
        } else {
            $data = collect();
            $rows = Type::where('IsDeleted', 0)
                ->where(function ($query) {
                    $query->whereNull('PARENT_ID')->orWhere('PARENT_ID', 0);
                })
                ->orderBy('DisplayOrder', 'asc')
                ->orderBy('TypeName', 'asc')
                ->get();
            foreach ($rows as $value) {
                $data->push($value);
                $level1 = Type::where(['IsDeleted' => 0, 'PARENT_ID' => $value->TYPE_ID])
                    ->orderBy('DisplayOrder', 'asc')
                    ->orderBy('TypeName', 'asc')->get();
                foreach ($level1 as $rowLevel1) {
                    $data->push($rowLevel1);
                    $level2 = Type::where(['IsDeleted' => 0, 'PARENT_ID' => $rowLevel1->TYPE_ID])
                        ->orderBy('DisplayOrder', 'asc')
                        ->orderBy('TypeName', 'asc')->get();
                    foreach ($level2 as $rowLevel2) {
                        $data->push($rowLevel2);
                    }
                }
            }
        }
        return BaseResult::withData($data);
    }
    public function getParentList()
    {
        $types = collect();
        $rows = Type::where('IsDeleted', 0)
            ->where(function ($query) {
                $query->whereNull('PARENT_ID')->orWhere('PARENT_ID', 0);
            })
            ->orderBy('DisplayOrder', 'asc')
            ->orderBy('TypeName', 'asc')
            ->get();
        foreach ($rows as $value) {
            $types->push($value);
            $level1 = Type::where(['IsDeleted' => 0, 'PARENT_ID' => $value->TYPE_ID])
                ->orderBy('DisplayOrder', 'asc')
                ->orderBy('TypeName', 'asc')->get();
            foreach ($level1 as $rowLevel1) {
                $types->push($rowLevel1);
            }
        }
        return BaseResult::withData($types);
    }
    public function add(Request $request) {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
            
            'TypeName' => [
                'required',
                Rule::unique('types')
                    ->where('IsDeleted', 0)
            ],
            'DisplayOrder' => 'required|numeric|min:1'
        );
        $customerMessage = [
            'unique' => 'The type name already exists',
        ];
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules,$customerMessage);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $type = new Type;
            try {
                $parentId = $request->PARENT_ID;
                $type->PARENT_ID = $parentId;
                // -- depth -------
                if ($parentId == 0) {
                    $type->Depth = 0;
                } else {
                    $parent = Type::find($parentId);
                    if ($parent) {
                        $type->Depth = $parent->Depth + 1;
                    } else {
                        $type->Depth = 0;
                    }
                }
                // -- end: depth --
                $type->TypeName = $request->TypeName;
                $type->DisplayOrder = $request->DisplayOrder;  
                $type->IsPublished = $request->has('IsPublished') ? true : false; 
                $type->IsDeleted = 0;
                $type->CreatedDate = now();
                $type->CreatedBy = $user->USE_ID;

                $type->save();
                return BaseResult::withData($type);
            } catch (\Exception $e) {
                return BaseResult::error(500, $e->getMessage());
            }
        }
    }
    public function update(Request $request) {
        //validate the info, create rules for the inputs
        $rules = array(
            'id' => 'numeric',
            
            'TypeName' => [
                'required',
                Rule::unique('types')->where('IsDeleted', 0)->ignore(Type::find($request->input('id')))
                    
            ],
            'DisplayOrder' => 'required|numeric|min:1'
        );
        $customerMessage = [
            'unique' => 'The type name already exists',
        ];
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules,$customerMessage);
        if ($validator->fails()) {
            return BaseResult::error(400, $validator->messages()->toJson());
        } else {
            $user = Session::get('user');
            $type = Type::find($request->input('id'));
            if ($type) {
                try {
                    $parentId = $request->PARENT_ID;
                    $type->PARENT_ID = $parentId;
                    // -- depth -------
                    if ($parentId == 0) {
                        $type->Depth = 0;
                    } else {
                        $parent = Type::find($parentId);
                        if ($parent) {
                            $type->Depth = $parent->Depth + 1;
                        } else {
                            $type->Depth = 0;
                        }
                    }
                    // -- end: depth --

                    $type->TypeName = $request->TypeName;
                    $type->DisplayOrder = $request->DisplayOrder; 
                    $type->IsPublished = $request->has('IsPublished') ? true : false;                
                    $type->IsDeleted = 0;
                    $type->UpdatedDate = now();
                    $type->UpdatedBy = $user->USE_ID;

                    $type->save();
                    return BaseResult::withData($type);
                } catch (\Exception $e) {
                    return BaseResult::error(500, $e->getMessage());
                }
            } else {
                return BaseResult::error(404, 'Data not found!.');
            }
        }
    }
    public function delete($id) {
        $type = Type::find($id);
        if ($type) {
            $user = Session::get('user');

            $type->IsDeleted = 1;
            $type->UpdatedDate = now();
            $type->UpdatedBy = $user->USE_ID;
            $type->save();
            return BaseResult::withData($type);
        } else {
            return BaseResult::error(404, 'Không tìm thấy dữ liệu!.');
        }
    }
}

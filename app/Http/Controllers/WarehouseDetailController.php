<?php

namespace App\Http\Controllers;
use App\Http\Resources\WarehouseDetailResource;
use App\Models\WarehouseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WarehouseDetailController extends Controller
{

    public static function routeName(){
        return Str::snake("WarehouseDetail");
    }
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        return WarehouseDetailResource::collection(WarehouseDetail::search($request)->sort($request)->paginate((request('per_page')??request('itemsPerPage'))??15));
    }
    public function store(Request $request)
    {
        if(!$this->user->is_permitted_to('store',WarehouseDetail::class,$request))
            return response()->json(['message'=>'not_permitted'],422);

        $validator = Validator::make($request->all(),WarehouseDetail::createRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $warehouseDetail = WarehouseDetail::create($validator->validated());
        if ($request->translations) {
            foreach ($request->translations as $translation)
                $warehouseDetail->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new WarehouseDetailResource($warehouseDetail);
    }
    public function show(Request $request,WarehouseDetail $warehouseDetail)
    {
        if(!$this->user->is_permitted_to('view',WarehouseDetail::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        return new WarehouseDetailResource($warehouseDetail);
    }
    public function update(Request $request, WarehouseDetail $warehouseDetail)
    {
        if(!$this->user->is_permitted_to('update',WarehouseDetail::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $validator = Validator::make($request->all(),WarehouseDetail::updateRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $warehouseDetail->update($validator->validated());
          if ($request->translations) {
            foreach ($request->translations as $translation)
                $warehouseDetail->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new WarehouseDetailResource($warehouseDetail);
    }
    public function destroy(Request $request,WarehouseDetail $warehouseDetail)
    {
        if(!$this->user->is_permitted_to('delete',WarehouseDetail::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $warehouseDetail->delete();

        return new WarehouseDetailResource($warehouseDetail);
    }
}

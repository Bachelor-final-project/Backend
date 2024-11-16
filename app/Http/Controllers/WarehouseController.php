<?php

namespace App\Http\Controllers;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WarehouseController extends Controller
{

    public static function routeName(){
        return Str::snake("Warehouse");
    }
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        return WarehouseResource::collection(Warehouse::search($request)->sort($request)->paginate((request('per_page')??request('itemsPerPage'))??15));
    }
    public function store(Request $request)
    {
        if(!$this->user->is_permitted_to('store',Warehouse::class,$request))
            return response()->json(['message'=>'not_permitted'],422);

        $validator = Validator::make($request->all(),Warehouse::createRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $warehouse = Warehouse::create($validator->validated());
        if ($request->translations) {
            foreach ($request->translations as $translation)
                $warehouse->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new WarehouseResource($warehouse);
    }
    public function show(Request $request,Warehouse $warehouse)
    {
        if(!$this->user->is_permitted_to('view',Warehouse::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        return new WarehouseResource($warehouse);
    }
    public function update(Request $request, Warehouse $warehouse)
    {
        if(!$this->user->is_permitted_to('update',Warehouse::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $validator = Validator::make($request->all(),Warehouse::updateRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $warehouse->update($validator->validated());
          if ($request->translations) {
            foreach ($request->translations as $translation)
                $warehouse->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new WarehouseResource($warehouse);
    }
    public function destroy(Request $request,Warehouse $warehouse)
    {
        if(!$this->user->is_permitted_to('delete',Warehouse::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $warehouse->delete();

        return new WarehouseResource($warehouse);
    }
}

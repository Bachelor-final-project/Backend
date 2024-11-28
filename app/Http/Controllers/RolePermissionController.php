<?php

namespace App\Http\Controllers;
use App\Http\Resources\RolePermissionResource;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RolePermissionController extends Controller
{

    public static function routeName(){
        return Str::snake("RolePermission");
    }
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        return RolePermissionResource::collection(RolePermission::search($request)->sort($request)->paginate((request('per_page')??request('itemsPerPage'))??15));
    }
    public function store(Request $request)
    {
        if(!$this->user->is_permitted_to('store',RolePermission::class,$request))
            return response()->json(['message'=>'not_permitted'],422);

        $validator = Validator::make($request->all(),RolePermission::createRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $rolePermission = RolePermission::create($validator->validated());
        if ($request->translations) {
            foreach ($request->translations as $translation)
                $rolePermission->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new RolePermissionResource($rolePermission);
    }
    public function show(Request $request,RolePermission $rolePermission)
    {
        if(!$this->user->is_permitted_to('view',RolePermission::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        return new RolePermissionResource($rolePermission);
    }
    public function update(Request $request, RolePermission $rolePermission)
    {
        if(!$this->user->is_permitted_to('update',RolePermission::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $validator = Validator::make($request->all(),RolePermission::updateRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $rolePermission->update($validator->validated());
          if ($request->translations) {
            foreach ($request->translations as $translation)
                $rolePermission->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new RolePermissionResource($rolePermission);
    }
    public function destroy(Request $request,RolePermission $rolePermission)
    {
        if(!$this->user->is_permitted_to('delete',RolePermission::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $rolePermission->delete();

        return new RolePermissionResource($rolePermission);
    }
}

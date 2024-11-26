<?php

namespace App\Http\Controllers;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PermissionController extends Controller
{

    public static function routeName(){
        return Str::snake("Permission");
    }
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        return PermissionResource::collection(Permission::search($request)->sort($request)->paginate((request('per_page')??request('itemsPerPage'))??15));
    }
    public function store(Request $request)
    {
        if(!$this->user->is_permitted_to('store',Permission::class,$request))
            return response()->json(['message'=>'not_permitted'],422);

        $validator = Validator::make($request->all(),Permission::createRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $permission = Permission::create($validator->validated());
        if ($request->translations) {
            foreach ($request->translations as $translation)
                $permission->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new PermissionResource($permission);
    }
    public function show(Request $request,Permission $permission)
    {
        if(!$this->user->is_permitted_to('view',Permission::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        return new PermissionResource($permission);
    }
    public function update(Request $request, Permission $permission)
    {
        if(!$this->user->is_permitted_to('update',Permission::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $validator = Validator::make($request->all(),Permission::updateRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $permission->update($validator->validated());
          if ($request->translations) {
            foreach ($request->translations as $translation)
                $permission->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new PermissionResource($permission);
    }
    public function destroy(Request $request,Permission $permission)
    {
        if(!$this->user->is_permitted_to('delete',Permission::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $permission->delete();

        return new PermissionResource($permission);
    }
}

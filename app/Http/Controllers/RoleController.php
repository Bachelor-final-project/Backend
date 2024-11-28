<?php

namespace App\Http\Controllers;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RoleController extends Controller
{

    public static function routeName(){
        return Str::snake("Role");
    }
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        return RoleResource::collection(Role::search($request)->sort($request)->paginate((request('per_page')??request('itemsPerPage'))??15));
    }
    public function store(Request $request)
    {
        if(!$this->user->is_permitted_to('store',Role::class,$request))
            return response()->json(['message'=>'not_permitted'],422);

        $validator = Validator::make($request->all(),Role::createRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $role = Role::create($validator->validated());
        if ($request->translations) {
            foreach ($request->translations as $translation)
                $role->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new RoleResource($role);
    }
    public function show(Request $request,Role $role)
    {
        if(!$this->user->is_permitted_to('view',Role::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        return new RoleResource($role);
    }
    public function update(Request $request, Role $role)
    {
        if(!$this->user->is_permitted_to('update',Role::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $validator = Validator::make($request->all(),Role::updateRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $role->update($validator->validated());
          if ($request->translations) {
            foreach ($request->translations as $translation)
                $role->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new RoleResource($role);
    }
    public function destroy(Request $request,Role $role)
    {
        if(!$this->user->is_permitted_to('delete',Role::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $role->delete();

        return new RoleResource($role);
    }
}

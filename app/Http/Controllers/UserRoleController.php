<?php

namespace App\Http\Controllers;
use App\Http\Resources\UserRoleResource;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserRoleController extends Controller
{

    public static function routeName(){
        return Str::snake("UserRole");
    }
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        return UserRoleResource::collection(UserRole::search($request)->sort($request)->paginate((request('per_page')??request('itemsPerPage'))??15));
    }
    public function store(Request $request)
    {
        if(!$this->user->is_permitted_to('store',UserRole::class,$request))
            return response()->json(['message'=>'not_permitted'],422);

        $validator = Validator::make($request->all(),UserRole::createRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $userRole = UserRole::create($validator->validated());
        if ($request->translations) {
            foreach ($request->translations as $translation)
                $userRole->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new UserRoleResource($userRole);
    }
    public function show(Request $request,UserRole $userRole)
    {
        if(!$this->user->is_permitted_to('view',UserRole::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        return new UserRoleResource($userRole);
    }
    public function update(Request $request, UserRole $userRole)
    {
        if(!$this->user->is_permitted_to('update',UserRole::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $validator = Validator::make($request->all(),UserRole::updateRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $userRole->update($validator->validated());
          if ($request->translations) {
            foreach ($request->translations as $translation)
                $userRole->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new UserRoleResource($userRole);
    }
    public function destroy(Request $request,UserRole $userRole)
    {
        if(!$this->user->is_permitted_to('delete',UserRole::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $userRole->delete();

        return new UserRoleResource($userRole);
    }
}

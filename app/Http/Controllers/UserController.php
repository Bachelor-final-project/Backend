<?php

namespace App\Http\Controllers;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public static function routeName(){
        return Str::snake("User");
    }
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        return view("dashboard.users.index");
    }
    // public function index(Request $request)
    // {
    //     return UserResource::collection(User::search($request)->sort($request)->paginate((request('per_page')??request('itemsPerPage'))??15));
    // }
    public function store(Request $request)
    {
        // return [
        //     'status' => 'success',
        //     'title' => 'تم اضافة المستخدم بنجاح',
        // ];
        if(!$this->user->is_permitted_to('store',User::class,$request))
            return response()->json(['message'=>'not_permitted'],422);

        $validator = Validator::make($request->all(),User::createRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $user = User::create($validator->validated());
        if ($request->translations) {
            foreach ($request->translations as $translation)
                $user->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new UserResource($user);
    }
    public function show(Request $request,User $user)
    {
        if(!$this->user->is_permitted_to('view',User::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        return new UserResource($user);
    }
    public function update(Request $request, User $user)
    {
        if(!$this->user->is_permitted_to('update',User::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $validator = Validator::make($request->all(),User::updateRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $user->update($validator->validated());
          if ($request->translations) {
            foreach ($request->translations as $translation)
                $user->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new UserResource($user);
    }
    public function create(Request $request, User $user)
    {
        
        return view('dashboard.users.create');
    }
    public function destroy(Request $request,User $user)
    {
        if(!$this->user->is_permitted_to('delete',User::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $user->delete();

        return new UserResource($user);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public static function routeName()
    {
        return Str::snake("User");
    }

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    public function index(Request $request)
    {
        return view("dashboard." . $this->routeName() . ".index", [
            'headers' => $this->getModelInstance()::headers(),

        ]);
    }

    public function indexApi(Request $request)
    {
        // dd(User::get());
        return UserResource::collection(User::search($request)->sort($request)->paginate((request('per_page') ?? request('itemsPerPage')) ?? 15));
        // return UserResource::collection(User::get());
    }

    public function create()
    {
        return view("dashboard." . $this->routeName() . ".create", [

            'data_to_send' => 'Hello, World!',
            $this->routeName() => $this->getModelInstance()
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        // if (!$this->user->is_permitted_to('store', User::class, $request))
        //     return response()->json(['message' => 'not_permitted'], 422);

        // $validator = Validator::make($request->all(), User::createRules($this->user));
        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }
        $user = User::create($request->validated());
        // if ($request->translations) {
        //     foreach ($request->translations as $translation)
        //         $user->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        // }
        return new UserResource($user);
    }

    public function show(Request $request, User $user)
    {
        return view("dashboard." . $this->routeName() . ".show", [
            'data_to_send' => 'Hello, World!',
            $this->routeName() => $user
        ]);
    }

    public function edit(User $user)
    {
        return view("dashboard." . $this->routeName() . ".edit", [

            'data_to_send' => 'Hello, World!',
            $this->routeName() => $user
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        // if (!$this->user->is_permitted_to('update', User::class, $request))
        //     return response()->json(['message' => 'not_permitted'], 422);
        // $validator = Validator::make($request->all(), User::updateRules($this->user));
        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }
        $user->update($request->validated());
        // if ($request->translations) {
        //     foreach ($request->translations as $translation)
        //         $user->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        // }
        return new UserResource($user);
    }

    public function destroy(Request $request, User $user)
    {
        if (!$this->user->is_permitted_to('delete', User::class, $request))
            return response()->json(['message' => 'not_permitted'], 422);
        $user->delete();

        return new UserResource($user);
    }
}

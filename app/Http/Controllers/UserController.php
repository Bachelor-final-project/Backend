<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;

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
        $users = User::search($request)->sort($request)->paginate($this->pagination);
        return Inertia::render(Str::studly("User") . '/Index', [
            "headers" => User::headers(),
            "items" => $users,

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
        return Inertia::render(Str::studly("User") . '/Create', [
            'type_options' => User::types()
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
        $data = $request->validated();
        $email = $data['email'];
        $user = User::withTrashed()->where('email', $email)->first();
        if ($user?->id) {
            $user->update(array_merge($data, ['deleted_at' => null]));
        } else {
            User::create($data);
        }

        // $user = User::create($request->validated());
        // if ($request->translations) {
        //     foreach ($request->translations as $translation)
        //         $user->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        // }
        return to_route('user.index')->with('res', ['message' => __('User Saved Seccessfully'), 'type' => 'success']);
    }

    public function show(Request $request, User $user)
    {
        return Inertia::render(Str::studly("User") . '/Show', [
            //'options' => $regions,
            'user' => $user->toArray()
        ]);
    }

    public function edit(User $user)
    {
        return Inertia::render(Str::studly("User") . '/Edit', [
            'status_options' => User::statuses(),
            'type_options' => User::types(),
            'user' => $user->toArray(),
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
        $validated = $request->validated();
    // dd($validated);
        if (array_key_exists('password', $validated) && empty($validated['password'])) {
            unset($validated['password']);
        }
        // $validated['is_active'] = !($validated['status'] != User::STATUSES['open']);
        // if($validated['status'] != User::STATUSES['open']) {
        //     $validated['is_active'] = false;
        // } else {
        //     $validated['is_active'] = true;
        // }
        $user->update($validated);
        // if ($request->translations) {
        //     foreach ($request->translations as $translation)
        //         $user->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        // }
        return back()->with('res', ['message' => __('User Updated Seccessfully'), 'type' => 'success']);
    }

    public function destroy(Request $request, User $user)
    {
        $user->delete();

        return back()->with('res', ['message' => __('User Deleted Seccessfully'), 'type' => 'success']);
    }
}

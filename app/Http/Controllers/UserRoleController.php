<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRoleRequest;
use App\Http\Requests\UpdateUserRoleRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class UserRoleController extends Controller
{
    public static function routeName(){
        return Str::snake("UserRole");
    }
     public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        return Inertia::render(Str::studly("UserRole").'/Index', [
            "headers" => UserRole::headers(),
            "items" => UserRole::search($request)->sort($request)->paginate($this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("UserRole").'/Create', [
            // 'options' => $regions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRoleRequest $request)
    {
        $data = $request->validated();
        UserRole::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('UserRole Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(UserRole $userRole)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserRole $userRole)
    {
        return Inertia::render(Str::studly("UserRole").'/Update', [
            //'options' => $regions,
            'userRole' => $userRole->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRoleRequest $request, UserRole $userRole)
    {
        $validated = $request->validated();
        
        $userRole->update($validated);
        return back()->with('res', ['message' => __('UserRole Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserRole $userRole)
    {
        $userRole->delete();
        return back()->with('res', ['message' => __('UserRole Deleted Seccessfully'), 'type' => 'success']);
    }
}

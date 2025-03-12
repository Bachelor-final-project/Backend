<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class RoleController extends Controller
{
    public static function routeName(){
        return Str::snake("Role");
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
        
        return Inertia::render(Str::studly("Role").'/Index', [
            "headers" => Role::headers(),
            "items" => Role::search($request)->sort($request)->paginate($request->per_page?? $this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("Role").'/Create', [
            // 'options' => $regions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $data = $request->validated();
        Role::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('Role Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Role $role)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return Inertia::render(Str::studly("Role").'/Update', [
            //'options' => $regions,
            'role' => $role->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $validated = $request->validated();
        
        $role->update($validated);
        return back()->with('res', ['message' => __('Role Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return back()->with('res', ['message' => __('Role Deleted Seccessfully'), 'type' => 'success']);
    }
}

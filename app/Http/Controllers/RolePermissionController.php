<?php

namespace App\Http\Controllers;

use App\Models\RolePermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRolePermissionRequest;
use App\Http\Requests\UpdateRolePermissionRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class RolePermissionController extends Controller
{
    public static function routeName(){
        return Str::snake("RolePermission");
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
        
        return Inertia::render(Str::studly("RolePermission").'/Index', [
            "headers" => RolePermission::headers(),
            "items" => RolePermission::search($request)->sort($request)->paginate($request->per_page?? $this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("RolePermission").'/Create', [
            // 'options' => $regions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRolePermissionRequest $request)
    {
        $data = $request->validated();
        RolePermission::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('RolePermission Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(RolePermission $rolePermission)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RolePermission $rolePermission)
    {
        return Inertia::render(Str::studly("RolePermission").'/Update', [
            //'options' => $regions,
            'rolePermission' => $rolePermission->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRolePermissionRequest $request, RolePermission $rolePermission)
    {
        $validated = $request->validated();
        
        $rolePermission->update($validated);
        return back()->with('res', ['message' => __('RolePermission Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RolePermission $rolePermission)
    {
        $rolePermission->delete();
        return back()->with('res', ['message' => __('RolePermission Deleted Seccessfully'), 'type' => 'success']);
    }
}

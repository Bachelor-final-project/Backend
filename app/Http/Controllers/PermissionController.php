<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class PermissionController extends Controller
{
    public static function routeName(){
        return Str::snake("Permission");
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
        
        return Inertia::render(Str::studly("Permission").'/Index', [
            "headers" => Permission::headers(),
            "items" => Permission::search($request)->sort($request)->paginate($request->per_page?? $this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("Permission").'/Create', [
            // 'options' => $regions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        $data = $request->validated();
        Permission::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('Permission Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Permission $permission)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return Inertia::render(Str::studly("Permission").'/Update', [
            //'options' => $regions,
            'permission' => $permission->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $validated = $request->validated();
        
        $permission->update($validated);
        return back()->with('res', ['message' => __('Permission Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return back()->with('res', ['message' => __('Permission Deleted Seccessfully'), 'type' => 'success']);
    }
}
